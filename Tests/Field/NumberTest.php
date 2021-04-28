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
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['salary' => null]);
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['salary' => []]);
        $this->assertCount(11, $result);
    }

    public function testFilterByNumberEq()
    {
        $this->dataSource->addField('salary', 'number', 'eq');
        $result = $this->filterDataSource(['salary' => 222222]);

        $this->assertCount(2, $result);
    }

    public function testFilterByNumberGt()
    {
        $this->dataSource->addField('salary', 'number', 'gt');
        $result = $this->filterDataSource(['salary' => 111111]);

        $this->assertCount(3, $result);
    }

    public function testFilterByNumberGte()
    {
        $this->dataSource->addField('salary', 'number', 'gte');
        $result = $this->filterDataSource(['salary' => 222222]);

        $this->assertCount(3, $result);
    }

    public function testFilterByNumberLt()
    {
        $this->dataSource->addField('salary', 'number', 'lt');
        $result = $this->filterDataSource(['salary' => 345]);

        $this->assertCount(2, $result);
    }

    public function testFilterByNumberLte()
    {
        $this->dataSource->addField('salary', 'number', 'lte');
        $result = $this->filterDataSource(['salary' => 345]);

        $this->assertCount(3, $result);
    }

    public function testFilterByNumberBetween()
    {
        $this->dataSource->addField('salary', 'number', 'between');
        $result = $this->filterDataSource(['salary' => [123, 783]]);

        $this->assertCount(7, $result);
    }
}
