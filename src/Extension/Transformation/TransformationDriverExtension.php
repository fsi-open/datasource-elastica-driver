<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Transformation;

use Elastica\ResultSet;
use FSi\Component\DataSource\Driver\DriverAbstractExtension;
use FSi\Component\DataSource\Driver\Elastica\ResultToModelTransformer;
use FSi\Component\DataSource\Driver\Elastica\TransformerInterface;
use FSi\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent\ResultEventArgs;

class TransformationDriverExtension extends DriverAbstractExtension
{
    /**
     * @var \FSi\Component\DataSource\Driver\Elastica\TransformerInterface
     */
    private $transformer;

    public function __construct(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

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
            $result = new ResultToModelTransformer($this->transformer, $result);
            $event->setResult($result);
        }
    }
}
