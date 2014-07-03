<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use Elastica\Client;
use Elastica\Document;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Driver\Elastica\Tests\DataSourceFactory;

class DateTimeTest extends \PHPUnit_Framework_TestCase
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
        $index = $client->getIndex('datetime_index');
        if ($index->exists()) {
            $index->delete();
        }
        $index->create();
        $type = $index->getType('datetime_type');

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
        $this->dataSource->addField('timestamp', 'datetime', 'eq');

        $result = $this->filterDataSource(array('timestamp' => ''));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('timestamp' => null));
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(array('timestamp' => array()));
        $this->assertEquals(11, count($result));
    }

    public function testFilterByDateTimeEq()
    {
        $this->dataSource->addField('timestamp', 'datetime', 'eq');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('2014-06-02T22:02:16+0200')));

        $this->assertEquals(1, count($result));
    }

    public function testFilterByDateTimeGt()
    {
        $this->dataSource->addField('timestamp', 'datetime', 'gt');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('2014-06-01T23:01:16+0200')));

        $this->assertEquals(10, count($result));
    }

    public function testFilterByDateTimeGte()
    {
        $this->dataSource->addField('timestamp', 'datetime', 'gte');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('2014-06-09T15:09:16+0200')));

        $this->assertEquals(2, count($result));
    }

    public function testFilterByDateTimeLt()
    {
        $this->dataSource->addField('timestamp', 'datetime', 'lt');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('2014-06-02T22:02:16+0200')));

        $this->assertEquals(1, count($result));
    }

    public function testFilterByDateTimeLte()
    {
        $this->dataSource->addField('timestamp', 'datetime', 'lte');
        $result = $this->filterDataSource(array('timestamp' => new \DateTime('2014-06-02T22:02:16+0200')));

        $this->assertEquals(2, count($result));
    }

    public function testFilterByDateTimeBetween()
    {
        $this->dataSource->addField('timestamp', 'datetime', 'between');
        $result = $this->filterDataSource(
            array(
                'timestamp' => array(
                    new \DateTime('2014-06-07T17:07:16+0200'),
                    new \DateTime('2014-06-10T14:10:16+0200'),
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
