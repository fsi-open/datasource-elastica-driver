<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use FSi\Component\DataSource\Driver\Elastica\Tests\BaseTest;
use FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures\Branch;

class EntityTest extends BaseTest
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->dataSource = $this->prepareIndex('entity_index', 'entity_type', array(), function ($fixture) {
            $fixture['branch']['idx'] = $fixture['branch']['id'];

            return $fixture;
        });
        $this->dataSource->addField('branch', 'entity', 'eq');
    }

    public function testFilterByEmptyParameter()
    {
        $result = $this->filterDataSource(array('branch' => ''));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('branch' => null));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('branch' => array()));
        $this->assertEquals(11, count($result));
    }

    public function testFindItemsByEntity()
    {
        $result = $this->filterDataSource(array('branch' => new Branch(2)));

        $this->assertEquals(2, count($result));
    }

    public function testFindItemsByEntityWithNonStandardId()
    {
        $this->dataSource->clearFields();
        $this->dataSource->addField('branch', 'entity', 'eq', array(
            'identifier_field' => 'idx'
        ));
        $result = $this->filterDataSource(array('branch' => new Branch(null, 2)));

        $this->assertEquals(2, count($result));
    }
}
