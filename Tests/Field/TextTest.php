<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use FSi\Component\DataSource\Driver\Elastica\Tests\BaseTest;

class TextTest extends BaseTest
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->dataSource = $this->prepareIndex('text_index', 'text_type');
        $this->dataSource->addField('about', 'text', 'match');
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

    public function testFindItemsByMultipleWordWithAndOperator()
    {
        $this->dataSource->clearFields();
        $this->dataSource->addField('about', 'text', 'match', array('operator' => 'and'));
        $result = $this->filterDataSource(array('about' => 'MarkA MarkC'));

        $this->assertEquals(1, count($result));
    }
}
