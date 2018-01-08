<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Ordering;

use FSi\Component\DataSource\Extension\Core\Ordering\Driver\DriverExtension;
use FSi\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent;

/**
 * Driver extension for ordering that loads fields extension.
 */
class OrderingDriverExtension extends DriverExtension implements EventSubscriberInterface
{
    public function getExtendedDriverTypes()
    {
        return ['elastica'];
    }

    protected function loadFieldTypesExtensions()
    {
        return [
            new FieldExtension(),
        ];
    }

    public static function getSubscribedEvents()
    {
        return [
            DriverEvents::PRE_GET_RESULT => ['preGetResult'],
        ];
    }

    public function preGetResult(DriverEvent\DriverEventArgs $event)
    {
        $fields = $event->getFields();
        $sortedFields = $this->sortFields($fields);

        /** @var \Elastica\Query $query */
        $query = $event->getDriver()->getQuery();

        foreach ($sortedFields as $fieldName => $direction) {
            if (!isset($fields[$fieldName])) {
                continue;
            }

            $field = $fields[$fieldName];
            $query->addSort([$field->getOption('field') => ['order' => $direction]]);
        }
    }
}
