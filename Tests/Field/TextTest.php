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
    public function setUp(): void
    {
        $this->dataSource = $this->prepareIndex('text_index');
        $this->dataSource->addField('about', 'text', 'match');
    }

    public function testFilterByEmptyParameter()
    {
        $result = $this->filterDataSource(['about' => '']);
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['about' => null]);
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['about' => []]);
        $this->assertCount(11, $result);
    }

    public function testFindItemsBySingleWord()
    {
        $result = $this->filterDataSource(['about' => 'lorem']);

        $this->assertCount(11, $result);
    }

    public function testFindItemsByMultipleWord()
    {
        $result = $this->filterDataSource(['about' => 'lorem dolor']);

        $this->assertCount(11, $result);
    }

    public function testFindByMultipleFields()
    {
        $this->dataSource->clearFields();
        $this->dataSource->addField('multi', 'text', 'match', ['field' => ['about', 'name']]);

        $result = $this->filterDataSource(['multi' => 'MarkA Janusz']);

        $this->assertCount(3, $result);
    }

    public function testFindItemsByMultipleWordWithAndOperator()
    {
        $this->dataSource->clearFields();
        $this->dataSource->addField('about', 'text', 'match', ['operator' => 'and']);
        $result = $this->filterDataSource(['about' => 'MarkA MarkC']);

        $this->assertCount(1, $result);
    }
}
