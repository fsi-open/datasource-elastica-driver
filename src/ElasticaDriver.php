<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\ResultSet;
use Elastica\SearchableInterface;
use FSi\Component\DataSource\Driver\DriverAbstract;
use RuntimeException;

class ElasticaDriver extends DriverAbstract
{
    /**
     * @var BoolQuery
     */
    private $filters;

    /**
     * @var BoolQuery
     */
    private $subQueries;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var SearchableInterface
     */
    private $searchable;

    /**
     * @var AbstractQuery
     */
    private $userSubQuery;

    /**
     * @var AbstractQuery
     */
    private $userFilter;

    /**
     * @var Query
     */
    private $masterQuery;

    public function __construct(
        array $extensions,
        SearchableInterface $searchable,
        AbstractQuery $userSubQuery = null,
        AbstractQuery $userFilter = null,
        Query $masterQuery = null
    ) {
        parent::__construct($extensions);

        $this->searchable = $searchable;
        $this->userSubQuery = $userSubQuery;
        $this->userFilter = $userFilter;
        $this->masterQuery = $masterQuery;
    }

    public function initResult()
    {
        $this->subQueries = new BoolQuery();
        $this->filters = new BoolQuery();
        $this->query = ($this->masterQuery === null) ? new Query() : $this->masterQuery;
    }

    public function buildResult($fields, $from, $limit): ResultSet
    {
        if ($this->userFilter !== null) {
            $this->filters->addMust($this->userFilter);
        }

        foreach ($fields as $field) {
            if (!$field instanceof ElasticaFieldInterface) {
                throw new RuntimeException(sprintf(
                    'All fields must be instances of "%s"',
                    ElasticaFieldInterface::class
                ));
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

        $tempFilters = $this->filters->getParams();
        if (!empty($tempFilters)) {
            $this->query->setPostFilter($this->filters);
        }

        if ($from > 0) {
            $this->query->setFrom($from);
        }
        if ($limit > 0) {
            $this->query->setSize($limit);
        }

        $resultSet = $this->searchable->search($this->query);

        return $resultSet;
    }

    public function getType()
    {
        return 'elastica';
    }

    public function getQuery(): Query
    {
        if (!$this->query) {
            throw new RuntimeException('Query is accessible only during preGetResult event.');
        }

        return $this->query;
    }
}
