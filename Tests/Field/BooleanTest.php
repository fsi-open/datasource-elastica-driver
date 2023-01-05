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

class BooleanTest extends BaseTest
{
    public function setUp(): void
    {
        $this->dataSource = $this->prepareIndex('text_index');
        $this->dataSource->addField('active', 'boolean', 'eq');
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

    public function testFilterByBoolean()
    {
        $result = $this->filterDataSource(['active' => 1]);

        $this->assertCount(3, $result);
    }
}
