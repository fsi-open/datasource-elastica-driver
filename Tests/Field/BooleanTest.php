<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use FSi\Component\DataSource\Driver\Elastica\Tests\BaseTest;

class BooleanTest extends BaseTest
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->dataSource = $this->prepareIndex('text_index', 'text_type');
        $this->dataSource->addField('active', 'boolean', 'eq');
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

    public function testFilterByBoolean()
    {
        $result = $this->filterDataSource(array('active' => true));

        $this->assertEquals(3, count($result));
    }
}
