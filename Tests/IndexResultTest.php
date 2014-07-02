<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use FSi\Component\DataSource\DataSource;
use FSi\Component\DataSource\Driver\Elastica\Driver;
use FSi\Component\DataSource\Driver\Elastica\Extension\Indexing\IndexingDriverExtension;

class IndexResultTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformResult()
    {
        $elasticaResultSet = $this->getMockBuilder('Elastica\ResultSet')
            ->disableOriginalConstructor()
            ->getMock();

        $searchable = $this->getMock('Elastica\SearchableInterface');
        $searchable->expects($this->any())
            ->method('search')
            ->willReturn($elasticaResultSet);

        $datasource = new DataSource(
            new Driver(
                array(new IndexingDriverExtension()),
                $searchable
            ),
            'test'
        );

        $result = $datasource->getResult();

        $this->assertNotInstanceOf('\Elastica\ResultSet', $result);
        $this->assertInstanceOf('\FSi\Component\DataSource\Driver\Elastica\Result', $result);
    }
}
