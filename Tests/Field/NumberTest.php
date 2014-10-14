<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

class NumberTest extends BaseFieldTest
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->dataSource = $this->prepareIndex('datetime_index', 'datetime_type');
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
}
