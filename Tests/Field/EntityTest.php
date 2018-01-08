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
use FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures\Branch;

class EntityTest extends BaseTest
{
    public function setUp()
    {
        $this->dataSource = $this->prepareIndex('entity_index', 'entity_type', null, function ($fixture) {
            $fixture['branch']['idx'] = $fixture['branch']['id'];

            return $fixture;
        });
        $this->dataSource->addField('branch', 'entity', 'eq');
    }

    public function testFilterByEmptyParameter()
    {
        $result = $this->filterDataSource(['branch' => '']);
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(['branch' => null]);
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(['branch' => []]);
        $this->assertEquals(11, count($result));
    }

    public function testFindItemsByEntity()
    {
        $result = $this->filterDataSource(['branch' => new Branch(2)]);

        $this->assertEquals(2, count($result));
    }

    public function testFindItemsByEntityWithNonStandardId()
    {
        $this->dataSource->clearFields();
        $this->dataSource->addField('branch', 'entity', 'eq', [
            'identifier_field' => 'idx'
        ]);
        $result = $this->filterDataSource(['branch' => new Branch(null, 2)]);

        $this->assertEquals(2, count($result));
    }
}
