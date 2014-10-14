<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use Elastica\Client;
use Elastica\Document;
use FSi\Component\DataSource\Driver\Elastica\Tests\BaseTest;

class DateTimeTest extends BaseTest
{
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

        $this->dataSource = $this->getDataSourceFactory()->createDataSource(
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
}
