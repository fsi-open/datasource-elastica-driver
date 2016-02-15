<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Filter\AbstractMulti;
use Elastica\Query\BoolQuery;

interface ElasticaFieldInterface
{
    public function buildQuery(BoolQuery $query, AbstractMulti $filter);
}
