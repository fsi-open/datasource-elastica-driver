<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Transformation;

use Doctrine\Common\Persistence\ManagerRegistry;
use Elastica\ResultSet;
use FSi\Component\DataSource\Driver\DriverAbstractExtension;
use FSi\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent\ResultEventArgs;

class TransformationDriverExtension extends DriverAbstractExtension
{
    /**
     * @var TransformerInterface
     */
    private $transformer;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(TransformerInterface $transformer, ManagerRegistry $registry)
    {
        $this->transformer = $transformer;
        $this->registry = $registry;
    }

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
            $result = new ResultToModelTransformer($this->transformer, $this->registry, $result);
            $event->setResult($result);
        }
    }
}
