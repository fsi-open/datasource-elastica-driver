<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Filter\AbstractFilter;
use Elastica\Filter\BoolAnd;
use Elastica\Index;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Bool;
use Elastica\Query;
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
     * @var \Elastica\Query\AbstractQuery
     */
    private $userSubQuery;

    /**
     * @var \Elastica\Filter\AbstractFilter
     */
    private $userFilter;
    /**
     * @var \Elastica\Query
     */
    private $masterQuery;

    /**
     * @param $extensions array with extensions
     * @param SearchableInterface $searchable
     * @param AbstractQuery $userSubQuery
     * @param AbstractFilter $userFilter
     * @param Query $masterQuery
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    public function __construct(
        $extensions,
        SearchableInterface $searchable,
        AbstractQuery $userSubQuery = null,
        AbstractFilter $userFilter = null,
        Query $masterQuery = null
    ) {
        parent::__construct($extensions);

        $this->searchable = $searchable;
        $this->userSubQuery = $userSubQuery;
        $this->userFilter = $userFilter;
        $this->masterQuery = $masterQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function initResult()
    {
        $this->subQueries = new Bool();
        $this->filters = new BoolAnd();
        $this->query = ($this->masterQuery === null) ? new Query() : $this->masterQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function buildResult($fields, $from, $limit)
    {
        if ($this->userFilter !== null) {
            $this->filters->addFilter($this->userFilter);
        }

        foreach ($fields as $field) {
            if (!$field instanceof FieldInterface) {
                throw new \RuntimeException(
                    sprintf('All fields must be instances of \FSi\Component\DataSource\Driver\Elastica\FieldInterface')
                );
            }

            $field->buildQuery($this->subQueries, $this->filters);
        }

        if ($this->userSubQuery !== null) {
            $this->subQueries->addMust($this->userSubQuery);
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

        return $resultSet;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'elastica';
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
