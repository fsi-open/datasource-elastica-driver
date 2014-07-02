<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Indexing;

use Elastica\ResultSet;
use FSi\Component\DataSource\Driver\DriverAbstractExtension;
use FSi\Component\DataSource\Driver\Elastica\ResultToModelTransformer;
use FSi\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent\ResultEventArgs;

class TransformDriverExtension extends DriverAbstractExtension
{
    public function getExtendedDriverTypes()
    {
        return array('elastica');
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(DriverEvents::POST_GET_RESULT => array('postGetResult', 1024));
    }

    public function postGetResult(ResultEventArgs $event)
    {
        $result = $event->getResult();
        /** @var \FSi\Component\DataSource\Driver\Elastica\Driver $driver */
        $driver = $event->getDriver();

        if ($result instanceof ResultSet) {
            $result = new ResultToModelTransformer($driver->getTransformer(), $result);
            $event->setResult($result);
        }
    }
}
