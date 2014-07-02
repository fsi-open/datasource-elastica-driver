<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

class Time extends DateTime
{
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
