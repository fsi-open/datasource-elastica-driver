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
    public function setUp(): void
    {
        $this->dataSource = $this->prepareIndex('entity_index', [], function ($fixture) {
            $fixture['branch']['idx'] = $fixture['branch']['id'];

            return $fixture;
        });
        $this->dataSource->addField('branch', 'entity', 'eq');
    }

    public function testFilterByEmptyParameter()
    {
        $result = $this->filterDataSource(['branch' => '']);
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['branch' => null]);
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['branch' => []]);
        $this->assertCount(11, $result);
    }

    public function testFindItemsByEntity()
    {
        $result = $this->filterDataSource(['branch' => new Branch(2)]);

        $this->assertCount(2, $result);
    }

    public function testFindItemsByEntityWithNonStandardId()
    {
        $this->dataSource->clearFields();
        $this->dataSource->addField('branch', 'entity', 'eq', [
            'identifier_field' => 'idx'
        ]);
        $result = $this->filterDataSource(['branch' => new Branch(null, 2)]);

        $this->assertCount(2, $result);
    }
}
