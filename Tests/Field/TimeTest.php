<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

class TimeTest extends BaseFieldTest
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $mapping = array(
            'timestamp' => array('type' => 'date', 'format' => 'basic_time_no_millis'),
        );
        $this->dataSource = $this->prepareIndex('time_index', 'time_index', $mapping, function ($fixture) {
            $time = new \DateTime($fixture['timestamp']);
            $fixture['timestamp'] = $time->format('HisO');

            return $fixture;
        });
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
}
