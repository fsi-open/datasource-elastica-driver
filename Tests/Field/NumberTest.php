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

class NumberTest extends BaseTest
{
    public function setUp(): void
    {
        $this->dataSource = $this->prepareIndex('datetime_index');
    }

    public function testFilterByEmptyParameter()
    {
        $this->dataSource->addField('salary', 'number', 'eq');

        $result = $this->filterDataSource(['salary' => '']);
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(['salary' => null]);
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(['salary' => []]);
        $this->assertEquals(11, count($result));
    }

    public function testFilterByNumberEq()
    {
        $this->dataSource->addField('salary', 'number', 'eq');
        $result = $this->filterDataSource(['salary' => 222222]);

        $this->assertEquals(2, count($result));
    }

    public function testFilterByNumberGt()
    {
        $this->dataSource->addField('salary', 'number', 'gt');
        $result = $this->filterDataSource(['salary' => 111111]);

        $this->assertEquals(3, count($result));
    }

    public function testFilterByNumberGte()
    {
        $this->dataSource->addField('salary', 'number', 'gte');
        $result = $this->filterDataSource(['salary' => 222222]);

        $this->assertEquals(3, count($result));
    }

    public function testFilterByNumberLt()
    {
        $this->dataSource->addField('salary', 'number', 'lt');
        $result = $this->filterDataSource(['salary' => 345]);

        $this->assertEquals(2, count($result));
    }

    public function testFilterByNumberLte()
    {
        $this->dataSource->addField('salary', 'number', 'lte');
        $result = $this->filterDataSource(['salary' => 345]);

        $this->assertEquals(3, count($result));
    }

    public function testFilterByNumberBetween()
    {
        $this->dataSource->addField('salary', 'number', 'between');
        $result = $this->filterDataSource(['salary' => [123, 783]]);

        $this->assertEquals(7, count($result));
    }
}
