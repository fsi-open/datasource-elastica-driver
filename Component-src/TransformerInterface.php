<?php
namespace FSi\Component\DataSource\Driver\Elastica;

interface TransformerInterface
{
    public function transform(array $objects);
}
