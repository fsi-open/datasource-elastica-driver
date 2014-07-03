<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Elastica\FieldInterface;

class Number extends AbstractField implements FieldInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'number';
    }
}
