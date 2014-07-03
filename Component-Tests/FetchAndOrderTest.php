<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Elastica\Client;
use Elastica\Document;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Extension\Core\Ordering\OrderingExtension;

class FetchAndOrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Component\DataSource\DataSource
     */
    private $dataSource;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $client  = new Client();
        $index = $client->getIndex('test_index');
        if ($index->exists()) {
            $index->delete();
        }
        $index->create();
        $type = $index->getType('test_type');

        $documents = array();
        $fixtures = require('Fixtures/documents.php');
        foreach ($fixtures as $id => $fixture) {
            $documents[] = new Document($id, $fixture);
        }
        $type->addDocuments($documents);
        $index->flush(true);

        $dataSourceFactory = new DataSourceFactory();
        $this->dataSource = $dataSourceFactory->getDataSourceFactory()->createDataSource(
            'elastica',
            array('index' => 'test_index', 'type' => 'test_type')
        );
        $this->dataSource
            ->addField('surname', 'text', 'like')
            ->addField('active', 'boolean', 'eq')
            ->addField('salary', 'number', 'gte')
            ->addField('about', 'text', 'like');
    }

    public function tearDown()
    {
    }

    public function testFetchingAllResults()
    {
        $this->assertEquals(11, count($this->dataSource->getResult()));
    }

    public function testFetchingPaginatedResults()
    {
        $this->dataSource->setMaxResults(5);
        $results = $this->dataSource->getResult();

        $this->assertEquals(11, count($results));

        $pageResultCount = 0;
        foreach ($results as $result) {
            $pageResultCount++;
        }

        $this->assertEquals(5, $pageResultCount);
    }

    public function testCombineMultipleFilters()
    {
        $this->dataSource->bindParameters(
            $this->parametersEnvelope(
                array(
                    'about' => 'lorem',
                    'active' => false,
                    'salary' => 222222
                )
            )
        );
        $result = $this->dataSource->getResult();

        $this->assertEquals(2, count($result));
    }

    public function testOrdering()
    {
        $this->dataSource->setMaxResults(20);
        $this->dataSource->bindParameters(
            array(
                $this->dataSource->getName() => array(
                    OrderingExtension::PARAMETER_SORT => array(
                        'salary' => 'asc',
                        'surname' => 'asc'
                    ),
                ),
            )
        );

        $result = $this->dataSource->getResult();

        $this->assertEquals(11, count($result));

        $expectedIds = array('p6', 'p10', 'p5', 'p8', 'p7', 'p11', 'p9', 'p1', 'p3', 'p2', 'p4');
        $actualIds = array();
        foreach ($result as $single) {
            /** @var \Elastica\Result $single */
            $actualIds[] = $single->getId();
        }

        $this->assertEquals($expectedIds, $actualIds);
    }

    private function parametersEnvelope(array $parameters)
    {
        return array(
            $this->dataSource->getName() => array(
                DataSourceInterface::PARAMETER_FIELDS => $parameters,
            ),
        );
    }
}
