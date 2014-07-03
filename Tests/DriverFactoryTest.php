<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Elastica\Client;
use FSi\Component\DataSource\Driver\DriverAbstract;
use FSi\Component\DataSource\Driver\DriverFactoryInterface;
use FSi\Component\DataSource\Driver\Elastica\DriverFactory;

class DriverFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testDriverCreation()
    {
        $factory = new DriverFactory(array());
        $this->assertTrue($factory instanceof DriverFactoryInterface);

        $driver = $factory->createDriver(array('searchable' => $this->getMock('\Elastica\SearchableInterface')));
        $this->assertTrue($driver instanceof DriverAbstract);
    }
}
