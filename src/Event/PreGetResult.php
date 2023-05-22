<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Event;

use Elastica\Query;
use FSi\Component\DataSource\Driver\DriverInterface;
use FSi\Component\DataSource\Driver\Event\DriverEventArgs;
use FSi\Component\DataSource\Field\FieldInterface;

/**
 * @template T
 * @template-extends DriverEventArgs<T>
 */
class PreGetResult extends DriverEventArgs
{
    private Query $query;

    /**
     * @param DriverInterface<T> $driver
     * @param array<FieldInterface> $fields
     * @param Query $query
     */
    public function __construct(DriverInterface $driver, array $fields, Query $query)
    {
        parent::__construct($driver, $fields);

        $this->query = $query;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }
}
