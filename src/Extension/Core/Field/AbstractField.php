<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Elastica\Exception\ElasticaDriverException;
use FSi\Component\DataSource\Field\FieldAbstractType;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;

abstract class AbstractField extends FieldAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildQuery(BoolQuery $query, BoolQuery $filter)
    {
        $data = $this->getCleanParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $fieldPath = $this->getField();

        switch ($this->getComparison()) {
            case 'eq':
                $termFilter = new Term();
                $termFilter->setTerm($fieldPath, $data);

                $filter->addMust($termFilter);
                break;
            case 'neq':
                $termFilter = new Term();
                $termFilter->setTerm($fieldPath, $data);

                $filter->addMustNot($termFilter);
                break;
            case 'between':
                if (!is_array($data)) {
                    throw new ElasticaDriverException;
                }
                $from = array_shift($data);
                $to = array_shift($data);
                $filter->addMust(new Range($fieldPath, array('gte' => $from, 'lte' => $to)));
                break;
            case 'lt':
            case 'lte':
            case 'gt':
            case 'gte':
                $filter->addMust(new Range($fieldPath, array($this->getComparison() => $data)));
                break;
            case 'in':
                $filter->addMust(
                    new Terms($fieldPath, $data)
                );
                break;
            case 'notIn':
                $data = $this->getCleanParameter();
                if (!is_array($data)) {
                    throw new ElasticaDriverException();
                }
                $filter->addMustNot(new Terms($fieldPath, $data));
                break;
            default:
                throw new ElasticaDriverException(
                    sprintf('Unexpected comparison type ("%s").', $this->getComparison())
                );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $field = $this;
        $this->getOptionsResolver()
            ->setDefault('field', null)
            ->setAllowedTypes('field', array('string', 'null'))
            ->setNormalizer('field', function ($options, $value) use ($field) {
                if (!empty($value)) {
                    return $value;
                }

                return $field->getName();
            })
        ;
    }

    protected function getField()
    {
        return $this->getOption('field');
    }

    protected function isEmpty($data)
    {
        if (is_array($data)) {
            $data = array_filter($data);
        }

        return ($data === array() || $data === '' || $data === null);
    }
}
