<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use Elastica\Client;
use Elastica\Document;
use FSi\Component\DataSource\Driver\Elastica\Tests\BaseTest;
use FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures\Branch;

class EntityTest extends BaseTest
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $client  = new Client();
        $index = $client->getIndex('entity_index');
        if ($index->exists()) {
            $index->delete();
        }
        $index->create();
        $type = $index->getType('entity_type');

        $documents = array();
        $fixtures = require(__DIR__ . '/../Fixtures/documents.php');
        foreach ($fixtures as $id => &$fixture) {
            $fixture['branch']['idx'] = $fixture['branch']['id'];
            $documents[] = new Document($id, $fixture);
        }
        $type->addDocuments($documents);
        $index->flush(true);

        $this->dataSource = $this->getDataSourceFactory()->createDataSource(
            'elastica',
            array('searchable' => $type)
        );
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
