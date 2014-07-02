<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\EventSubscriber;

use FSi\Component\DataSource\Driver\Elastica\Result;
use FSi\Component\DataSource\Driver\Elastica\ResultSet;
use FSi\Component\DataSource\Event\DriverEvent\ResultEventArgs;
use FSi\Component\DataSource\Event\DriverEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ResultIndexer implements EventSubscriberInterface
{
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
            $result = new Result($result->getResult());
            $event->setResult($result);
        }
    }
}
