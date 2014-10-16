<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

class Date extends DateTime
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'between');

    /**
     * {@inheritdoc}
     */
    protected function getFormat()
    {
        return 'Y-m-d';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'date';
    }
}
