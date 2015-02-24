<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use FSi\Component\DataSource\DataSource;
use FSi\Component\DataSource\Driver\Elastica\ElasticaDriver;
use FSi\Component\DataSource\Driver\Elastica\Extension\Transformation\TransformationDriverExtension;
use FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures\Branch;
use FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures\Transformer;

class TransformResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    private $registry;

    /**
     * @var \Elastica\SearchableInterface
     */
    private $searchable;

    public function setUp()
    {
        $elasticaResultSet = $this->getMockBuilder('Elastica\ResultSet')
            ->disableOriginalConstructor()
            ->getMock();
        $elasticaResultSet->expects($this->any())
            ->method('getResults')
            ->willReturn(array(new Branch(11, 11), new Branch(12, 12), new Branch(13, 13), new Branch(14, 14)));

        $this->searchable = $this->getMock('Elastica\SearchableInterface');
        $this->searchable->expects($this->any())
            ->method('search')
            ->willReturn($elasticaResultSet);

        $metadata = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $metadata->isMappedSuperclass = false;
        $metadata->rootEntityName = 'FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures\Branch';
        $metadata->expects($this->any())
            ->method('getIdentifierFieldNames')
            ->willReturn(array('id'));

        $metadataFactory = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadataFactory');
        $metadataFactory->expects($this->any())
            ->method('getMetadataFor')
            ->with('FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures\Branch')
            ->willReturn($metadata);

        $manager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $manager->expects($this->any())
            ->method('getClassMetadata')
            ->willReturn($metadata);
        $manager->expects($this->any())
            ->method('getMetadataFactory')
            ->willReturn($metadataFactory);

        $this->registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $this->registry->expects($this->any())
            ->method('getManagerForClass')
            ->willReturn($manager);
    }
    
    public function testTransformResult()
    {
        $datasource = new DataSource(
            new ElasticaDriver(
                array(new TransformationDriverExtension(new Transformer(), $this->registry)),
                $this->searchable
            ),
            'test'
        );

        $result = $datasource->getResult();

        $this->assertNotInstanceOf('\Elastica\ResultSet', $result);
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $result);
    }

    public function testSetCollectionIndexes()
    {
        $datasource = new DataSource(
            new ElasticaDriver(
                array(new TransformationDriverExtension(new Transformer(), $this->registry)),
                $this->searchable
            ),
            'test'
        );

        $result = $datasource->getResult();

        $this->assertEquals(array(11, 12, 13, 14), array_keys($result->toArray()));
    }
}
