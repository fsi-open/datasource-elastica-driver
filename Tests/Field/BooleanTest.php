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
    public function setUp()
    {
        $this->dataSource = $this->prepareIndex('text_index', 'text_type');
        $this->dataSource->addField('active', 'boolean', 'eq');
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

    public function testFilterByBoolean()
    {
        $result = $this->filterDataSource(['active' => true]);

        $this->assertEquals(3, count($result));
    }
}
