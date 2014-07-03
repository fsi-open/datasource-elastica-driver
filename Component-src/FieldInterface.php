<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Filter\AbstractMulti;
use Elastica\Query\Bool;

interface FieldInterface
{
    public function buildQuery(Bool $query, AbstractMulti $filter);
}
