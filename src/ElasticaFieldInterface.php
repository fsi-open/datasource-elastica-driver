<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Query\BoolQuery;

interface ElasticaFieldInterface
{
    public function buildQuery(BoolQuery $query, BoolQuery $filter);
}
