<?php

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
    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes()
    {
        return array('elastica');
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypesExtensions()
    {
        return array(
            new FieldExtension(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            DriverEvents::PRE_GET_RESULT => array('preGetResult'),
        );
    }

    /**
     * {@inheritdoc}
     */
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
            $query->addSort(array($field->getOption('field') => array('order' => $direction)));
        }
    }
}
