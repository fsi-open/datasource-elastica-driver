<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use Elastica\Client;
use Elastica\Document;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Driver\Elastica\Tests\DataSourceFactory;

class NumberTest extends \PHPUnit_Framework_TestCase
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
        $index = $client->getIndex('number_index');
        if ($index->exists()) {
            $index->delete();
        }
        $index->create();
        $type = $index->getType('number_type');

        $documents = array();
        $fixtures = require(__DIR__ . '/../Fixtures/documents.php');
        foreach ($fixtures as $id => $fixture) {
            $documents[] = new Document($id, $fixture);
        }
        $type->addDocuments($documents);
        $index->flush(true);

        $dataSourceFactory = new DataSourceFactory();
        $this->dataSource = $dataSourceFactory->getDataSourceFactory()->createDataSource(
            'elastica',
            array('searchable' => $type)
        );
    }

    public function testFilterByEmptyParameter()
    {
        $this->dataSource->addField('salary', 'number', 'eq');

        $result = $this->filterDataSource(array('salary' => ''));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('salary' => null));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('salary' => array()));
        $this->assertEquals(11, count($result));
    }

    public function testFilterByNumberEq()
    {
        $this->dataSource->addField('salary', 'number', 'eq');
        $result = $this->filterDataSource(array('salary' => 222222));

        $this->assertEquals(2, count($result));
    }

    public function testFilterByNumberGt()
    {
        $this->dataSource->addField('salary', 'number', 'gt');
        $result = $this->filterDataSource(array('salary' => 111111));

        $this->assertEquals(3, count($result));
    }

    public function testFilterByNumberGte()
    {
        $this->dataSource->addField('salary', 'number', 'gte');
        $result = $this->filterDataSource(array('salary' => 222222));

        $this->assertEquals(3, count($result));
    }

    public function testFilterByNumberLt()
    {
        $this->dataSource->addField('salary', 'number', 'lt');
        $result = $this->filterDataSource(array('salary' => 345));

        $this->assertEquals(2, count($result));
    }

    public function testFilterByNumberLte()
    {
        $this->dataSource->addField('salary', 'number', 'lte');
        $result = $this->filterDataSource(array('salary' => 345));

        $this->assertEquals(3, count($result));
    }

    public function testFilterByNumberBetween()
    {
        $this->dataSource->addField('salary', 'number', 'between');
        $result = $this->filterDataSource(
            array(
                'salary' => array(123, 783)
            )
        );

        $this->assertEquals(7, count($result));
    }

    private function filterDataSource($parameters)
    {
        $this->dataSource->bindParameters(
            $this->parametersEnvelope($parameters)
        );

        return $this->dataSource->getResult();
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
