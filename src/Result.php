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

    /**
     * Returns whether aggregations exist
     *
     * @return boolean Aggregation existence
     */
    public function hasAggregations()
    {
        return $this->resultSet->hasAggregations();
    }

    /**
     * Returns all aggregation results
     *
     * @return array
     */
    public function getAggregations()
    {
        return $this->resultSet->getAggregations();
    }

    /**
     * Retrieve a specific aggregation from this result set
     * @param string $name the name of the desired aggregation
     * @return array
     * @throws \Elastica\Exception\InvalidException if an aggregation by the given name cannot be found
     */
    public function getAggregation($name)
    {
        return $this->resultSet->getAggregation($name);
    }
}
