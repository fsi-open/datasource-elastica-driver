<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Transformation;

interface TransformerInterface
{
    /**
     * @param array $objects array of elastica objects
     * @return array of model objects
     */
    public function transform(array $objects);
}
