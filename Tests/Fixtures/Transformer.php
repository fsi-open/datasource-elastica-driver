<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures;

use FSi\Component\DataSource\Driver\Elastica\Extension\Transformation\TransformerInterface;

class Transformer implements TransformerInterface
{
    public function transform(array $objects)
    {
        return $objects;
    }
}
