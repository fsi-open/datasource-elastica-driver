<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;

class Text extends AbstractField implements ElasticaFieldInterface
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('match');

    /**
     * {@inheritdoc}
     */
    public function buildQuery(BoolQuery $query, BoolQuery $filter)
    {
        $data = $this->getCleanParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $field = $this->getField();
        if (is_array($field)) {
            $match = new Query\MultiMatch();
            $match->setFields($field);
            $match->setQuery($data);
            $match->setOperator($this->getOption('operator'));
        } else {
            $match = new Query\Match();
            $match->setFieldQuery($field, $data);
            $match->setFieldOperator($field, $this->getOption('operator'));
        }

        $query->addMust($match);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        parent::initOptions();

        $this->getOptionsResolver()
            ->setDefaults(array('operator' => 'or'))
            ->setAllowedTypes('field', array('array', 'string', 'null'))
            ->setAllowedValues('operator', array('or', 'and'))
        ;
    }
}
