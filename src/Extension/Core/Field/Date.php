<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

class Date extends DateTime
{
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
