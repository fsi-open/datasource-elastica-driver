<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use Elastica\Client;
use Elastica\Document;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Driver\Elastica\Tests\DataSourceFactory;

class TextTest extends \PHPUnit_Framework_TestCase
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
        $index = $client->getIndex('text_index');
        if ($index->exists()) {
            $index->delete();
        }
        $index->create();
        $type = $index->getType('text_type');

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
            array('index' => 'text_index', 'type' => 'text_type')
        );
        $this->dataSource->addField('about', 'text', 'like');
    }

    public function testFilterByEmptyParameter()
    {
        $result = $this->filterDataSource(array('about' => ''));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('about' => null));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('about' => array()));
        $this->assertEquals(11, count($result));
    }

    public function testFindItemsBySingleWord()
    {
        $result = $this->filterDataSource(array('about' => 'lorem'));

        $this->assertEquals(11, count($result));
    }

    public function testFindItemsByMultipleWord()
    {
        $result = $this->filterDataSource(array('about' => 'lorem dolor'));

        $this->assertEquals(11, count($result));
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
