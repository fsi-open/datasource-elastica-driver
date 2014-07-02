<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\ResultSet;

class Result implements \IteratorAggregate, \Countable
{
    /**
     * @var \Elastica\ResultSet
     */
    private $resultSet;

    public function __construct(ResultSet $resultSet)
    {
        $this->resultSet = $resultSet;
    }

    public function count()
    {
        return $this->resultSet->getTotalHits();
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->resultSet->getResults());
    }
}
