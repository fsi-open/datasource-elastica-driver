<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use Elastica\Client;
use Elastica\Document;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Driver\Elastica\Tests\DataSourceFactory;

class TimeTest extends \PHPUnit_Framework_TestCase
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
        $index = $client->getIndex('time_index');
        if ($index->exists()) {
            $index->delete();
        }
        $index->create();
        $type = $index->getType('time_type');
        $type->setMapping(array(
            'timestamp' => array('type' => 'date', 'format' => 'basic_time_no_millis'),
        ));

        $documents = array();
        $fixtures = require(__DIR__ . '/../Fixtures/documents.php');
        foreach ($fixtures as $id => $fixture) {
            $time = new \DateTime($fixture['timestamp']);
            $fixture['timestamp'] = $time->format('HisO');
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
        $this->dataSource->addField('timestamp', 'time', 'eq');

        $result = $this->filterDataSource(array('timestamp' => ''));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('timestamp' => null));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('timestamp' => array()));
        $this->assertEquals(11, count($result));
    }

    public function testFilterByTimeEq()
    {
        $this->dataSource->addField('timestamp', 'time', 'eq');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('T23:01:16+0200')));

        $this->assertEquals(1, count($result));
    }

    public function testFilterByTimeGt()
    {
        $this->dataSource->addField('timestamp', 'time', 'gt');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('T22:02:16+0200')));

        $this->assertEquals(2, count($result));
    }

    public function testFilterByTimeGte()
    {
        $this->dataSource->addField('timestamp', 'time', 'gte');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('T22:02:16+0200')));

        $this->assertEquals(3, count($result));
    }

    public function testFilterByTimeLt()
    {
        $this->dataSource->addField('timestamp', 'time', 'lt');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('T22:02:16+0200')));

        $this->assertEquals(8, count($result));
    }

    public function testFilterByTimeLte()
    {
        $this->dataSource->addField('timestamp', 'time', 'lte');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('T22:02:16+0200')));

        $this->assertEquals(9, count($result));
    }

    public function testFilterByTimeBetween()
    {
        $this->dataSource->addField('timestamp', 'time', 'between');
        $result = $this->filterDataSource(
            array(
                'timestamp' => array(
                    new \DateTime('T14:10:16+0200'),
                    new \DateTime('T17:07:16+0200'),
                )
            )
        );

        $this->assertEquals(4, count($result));
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
