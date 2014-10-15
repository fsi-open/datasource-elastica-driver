<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Filter\AbstractMulti;
use Elastica\Query\Bool;

interface ElasticaFieldInterface
{
    public function buildQuery(Bool $query, AbstractMulti $filter);
}
