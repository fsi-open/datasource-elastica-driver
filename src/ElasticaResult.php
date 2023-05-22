<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica;

use ArrayIterator;
use Countable;
use FSi\Component\DataSource\Result;
use Iterator;
use Elastica\ResultSet;

/**
 * @template T
 * @implements Result<T>
 */
class ElasticaResult implements Countable, Result
{
    private ResultSet $resultSet;

    public function __construct(ResultSet $resultSet)
    {
        $this->resultSet = $resultSet;
    }

    public function count(): int
    {
        return $this->resultSet->getTotalHits();
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->resultSet->getResults());
    }

    public function hasAggregations(): bool
    {
        return $this->resultSet->hasAggregations();
    }

    public function getAggregations(): array
    {
        return $this->resultSet->getAggregations();
    }

    public function getAggregation(string $name): array
    {
        return $this->resultSet->getAggregation($name);
    }
}
