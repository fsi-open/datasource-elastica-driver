<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

class Time extends DateTime
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'between');

    protected function getFormat()
    {
        return 'HisO';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'time';
    }
}
