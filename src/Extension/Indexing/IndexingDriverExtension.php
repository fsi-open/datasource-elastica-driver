<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Indexing;

use Elastica\ResultSet;
use FSi\Component\DataSource\Driver\DriverAbstractExtension;
use FSi\Component\DataSource\Driver\Elastica\Result;
use FSi\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent;
use FSi\Component\DataSource\Event\DriverEvent\ResultEventArgs;

class IndexingDriverExtension extends DriverAbstractExtension
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
    public static function getSubscribedEvents()
    {
        return array(DriverEvents::POST_GET_RESULT => array('postGetResult', 1024));
    }

    public function postGetResult(ResultEventArgs $event)
    {
        $result = $event->getResult();

        if ($result instanceof ResultSet) {
            $result = new Result($result);
            $event->setResult($result);
        }
    }
}
