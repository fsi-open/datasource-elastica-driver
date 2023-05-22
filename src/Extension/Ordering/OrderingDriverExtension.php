<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Ordering;

use FSi\Component\DataSource\DataSourceEventSubscriberInterface;
use FSi\Component\DataSource\Driver\Elastica\ElasticaDriver;
use FSi\Component\DataSource\Driver\Elastica\Event\PreGetResult;
use FSi\Component\DataSource\Extension\Ordering\Storage;

/**
 * Driver extension for ordering that loads fields extension.
 */
class OrderingDriverExtension implements DataSourceEventSubscriberInterface
{
    private Storage $storage;

    public static function getPriority(): int
    {
        return 0;
    }

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(PreGetResult $event): void
    {
        $driver = $event->getDriver();
        if (false === $driver instanceof ElasticaDriver) {
            return;
        }

        $fields = $event->getFields();
        $sortedFields = $this->storage->sortFields($fields);

        $query = $event->getQuery();
        foreach ($sortedFields as $fieldName => $direction) {
            $field = $fields[$fieldName];
            $query->addSort([(string) $field->getOption('field') => ['order' => $direction]]);
        }
    }
}
