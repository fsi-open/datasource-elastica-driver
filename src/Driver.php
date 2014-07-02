<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Filter\BoolAnd;
use Elastica\Index;
use Elastica\Query;
use Elastica\Query\Bool;
use Elastica\SearchableInterface;
use FSi\Component\DataSource\Driver\DriverAbstract;

class Driver extends DriverAbstract
{
    /**
     * @var \Elastica\Filter\BoolAnd
     */
    private $filters;

    /**
     * @var \Elastica\Query\Bool
     */
    private $subQueries;

    /**
     * @var \Elastica\Query
     */
    private $query;

    /**
     * @var \Elastica\SearchableInterface
     */
    private $searchable;

    /**
     * @var null
     */
    private $transformer;

    /**
     * @param $extensions array with extensions
     * @param SearchableInterface              $searchable
     * @param null                             $transformer
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    public function __construct($extensions, SearchableInterface $searchable, $transformer = null)
    {
        parent::__construct($extensions);

        $this->searchable = $searchable;
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function initResult()
    {
        $this->subQueries = new Bool();
        $this->filters = new BoolAnd();
        $this->query = new Query();
    }

    /**
     * {@inheritdoc}
     */
    public function buildResult($fields, $from, $limit)
    {
        foreach ($fields as $field) {
            if (!$field instanceof FieldInterface) {
                throw new \RuntimeException(
                    sprintf('All fields must be instances of \FSi\Component\DataSource\Driver\Elastica\FieldInterface')
                );
            }

            $field->buildQuery($this->subQueries, $this->filters);
        }

        if ($this->subQueries->hasParam('should') || $this->subQueries->hasParam('must') ||
            $this->subQueries->hasParam('must_not')) {
            $this->query->setQuery($this->subQueries);
        }

        $tempFilters = $this->filters->getFilters();
        if (!empty($tempFilters)) {
            $this->query->setFilter($this->filters);
        }

        if ($limit > 0) {
            $this->query->setFrom($from);
            $this->query->setSize($limit);
        }

        $resultSet = $this->searchable->search($this->query);

        return new ResultSet($resultSet);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'elastica';
    }

    /**
     * @return null
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /**
     * @return \Elastica\Query
     */
    public function getQuery()
    {
        if (!$this->query) {
            throw new \RuntimeException('Query is accessible only during preGetResult event.');
        }

        return $this->query;
    }
}
