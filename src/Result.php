<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Doctrine\Common\Collections\ArrayCollection;
use Elastica\ResultSet;

class Result extends ArrayCollection
{
    /**
     * @var \Elastica\ResultSet
     */
    private $resultSet;

    public function __construct(ResultSet $resultSet)
    {
        $this->resultSet = $resultSet;

        parent::__construct($this->resultSet);
    }

    public function count()
    {
        return $this->resultSet->getTotalHits();
    }
}
