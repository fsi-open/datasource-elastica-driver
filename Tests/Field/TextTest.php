<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use FSi\Component\DataSource\Driver\Elastica\Tests\BaseTest;

class TextTest extends BaseTest
{
    public function setUp()
    {
        $this->dataSource = $this->prepareIndex('text_index', 'text_type');
        $this->dataSource->addField('about', 'text', 'match');
    }

    public function testFilterByEmptyParameter()
    {
        $result = $this->filterDataSource(['about' => '']);
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(['about' => null]);
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(['about' => []]);
        $this->assertEquals(11, count($result));
    }

    public function testFindItemsBySingleWord()
    {
        $result = $this->filterDataSource(['about' => 'lorem']);

        $this->assertEquals(11, count($result));
    }

    public function testFindItemsByMultipleWord()
    {
        $result = $this->filterDataSource(['about' => 'lorem dolor']);

        $this->assertEquals(11, count($result));
    }

    public function testFindByMultipleFields()
    {
        $this->dataSource->clearFields();
        $this->dataSource->addField('multi', 'text', 'match', ['field' => ['about', 'name']]);

        $result = $this->filterDataSource(['multi' => 'MarkA Janusz']);

        $this->assertEquals(3, count($result));
    }

    public function testFindItemsByMultipleWordWithAndOperator()
    {
        $this->dataSource->clearFields();
        $this->dataSource->addField('about', 'text', 'match', ['operator' => 'and']);
        $result = $this->filterDataSource(['about' => 'MarkA MarkC']);

        $this->assertEquals(1, count($result));
    }
}
