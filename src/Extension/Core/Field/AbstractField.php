<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Filter\AbstractMulti;
use Elastica\Query\Bool;
use FSi\Component\DataSource\Driver\Elastica\Exception\ElasticaDriverException;
use FSi\Component\DataSource\Field\FieldAbstractType;
use Elastica\Filter\Range;
use Elastica\Filter\Term;
use Elastica\Filter\BoolNot;
use Elastica\Filter\Terms;
use Elastica\Query;

abstract class AbstractField extends FieldAbstractType
{
    public function buildQuery(Bool $query, AbstractMulti $filter)
    {
        $data = $this->getCleanParameter();
        if ($data === array() || $data === '' || $data === null) {
            return;
        }

        $fieldPath = $this->getField();

        switch ($this->getComparison()) {
            case 'eq':
                $termFilter = new Term();
                $termFilter->setTerm($fieldPath, $data);

                $filter->addFilter($termFilter);
                break;
            case 'neq':
                $termFilter = new Term();
                $termFilter->setTerm($fieldPath, $data);

                $filter->addFilter(
                    new BoolNot($termFilter)
                );
                break;
            case 'between':
                if (!is_array($data)) {
                    throw new ElasticaDriverException;
                }
                $from = array_shift($data);
                $to = array_shift($data);
                $filter->addFilter(new Range($fieldPath, array('gte' => $from, 'lte' => $to)));
                break;
            case 'lt':
            case 'lte':
            case 'gt':
            case 'gte':
                $filter->addFilter(new Range($fieldPath, array($this->getComparison() => $data)));
                break;
            case 'in':
                $filter->addFilter(
                    new Terms($fieldPath, $data)
                );
                break;
            case 'notIn':
                $data = $this->getCleanParameter();
                if (!is_array($data)) {
                    throw new ElasticaDriverException();
                }
                $filter->addFilter(
                    new BoolNot(
                        new Terms($fieldPath, $data)
                    )
                );
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
            ->setOptional(array('field'))
            ->setAllowedTypes(
                array(
                    'field' => array('string', 'null')
                )
            )
            ->setNormalizers(
                array(
                    'field' => function ($options, $value) use ($field) {
                        if (!empty($value)) {
                            return $value;
                        }

                        return $field->getName();
                    }
                )
            )
        ;
    }

    protected function getField()
    {
        return $this->getOption('field');
    }
}
