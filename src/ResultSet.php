<?php

namespace FSi\Component\DataSource\Driver\Elastica;

class ResultSet implements \IteratorAggregate, \Countable
{
    private $result;

    public function __construct(\Elastica\ResultSet $result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->result->getResults());
    }

    /**
     * @return \Elastica\ResultSet
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->result->getTotalHits();
    }
}
