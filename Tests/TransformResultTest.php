<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use FSi\Component\DataSource\DataSource;
use FSi\Component\DataSource\Driver\Elastica\Driver;
use FSi\Component\DataSource\Driver\Elastica\Extension\Indexing\TransformDriverExtension;
use FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures\Transformer;

class TransformResultTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformResult()
    {
        $elasticaResultSet = $this->getMockBuilder('Elastica\ResultSet')
            ->disableOriginalConstructor()
            ->getMock();
        $elasticaResultSet->expects($this->any())
            ->method('getResults')
            ->willReturn(array(1, 2, 3, 4));

        $searchable = $this->getMock('Elastica\SearchableInterface');
        $searchable->expects($this->any())
            ->method('search')
            ->willReturn($elasticaResultSet);

        $datasource = new DataSource(
            new Driver(
                array(new TransformDriverExtension(new Transformer())),
                $searchable
            ),
            'test'
        );

        $result = $datasource->getResult();

        $this->assertNotInstanceOf('\Elastica\ResultSet', $result);
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $result);
    }
}
