<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Doctrine\Common\Collections\ArrayCollection;
use Elastica\ResultSet;

class ResultToModelTransformer extends ArrayCollection
{
    /**
     * @var \Elastica\ResultSet
     */
    private $resultSet;

    public function __construct(TransformerInterface $transformer, ResultSet $resultSet)
    {
        $this->resultSet = $resultSet;

        parent::__construct($transformer->transform($this->resultSet->getResults()));
    }

    public function count()
    {
        return $this->resultSet->getTotalHits();
    }
}
