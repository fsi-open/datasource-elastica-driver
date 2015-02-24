<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Transformation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Elastica\ResultSet;
use FSi\Component\DataIndexer\DoctrineDataIndexer;

class ResultToModelTransformer extends ArrayCollection
{
    /**
     * @var \Elastica\ResultSet
     */
    private $resultSet;

    public function __construct(TransformerInterface $transformer, ManagerRegistry $registry, ResultSet $resultSet)
    {
        $this->resultSet = $resultSet;

        $data = $transformer->transform($this->resultSet->getResults());

        $result = array();
        if (count($data)) {
            $firstElement = current($data);
            $dataIndexer =  is_object($firstElement)
                ? new DoctrineDataIndexer($registry, get_class($firstElement))
                : null;

            foreach ($data as $key => $element) {
                $index = isset($dataIndexer) ? $dataIndexer->getIndex($element) : $key;
                $result[$index] = $element;
            }
        }

        parent::__construct($result);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->resultSet->getTotalHits();
    }
}
