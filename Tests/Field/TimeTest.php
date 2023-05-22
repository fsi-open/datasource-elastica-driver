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

class TimeTest extends BaseTest
{
    public function setUp(): void
    {
        $mapping = [
            'timestamp' => ['type' => 'date', 'format' => 'basic_time_no_millis'],
        ];
        $this->dataSource = $this->prepareIndex('time_index', $mapping, function ($fixture) {
            if (null !== $fixture['timestamp']) {
                $time = new \DateTime($fixture['timestamp']);
                $fixture['timestamp'] = $time->format('HisO');
            }

            return $fixture;
        });
    }

    public function testFilterByEmptyParameter(): void
    {
        $this->dataSource->addField('timestamp', 'time', ['comparison' => 'eq']);

        $result = $this->filterDataSource(['timestamp' => '']);
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['timestamp' => null]);
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['timestamp' => []]);
        $this->assertCount(11, $result);
    }

    public function testFilterByTimeEq(): void
    {
        $this->dataSource->addField('timestamp', 'time', ['comparison' => 'eq']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T23:01:16+0200')]);

        $this->assertCount(1, $result);
    }

    public function testFilterByTimeGt(): void
    {
        $this->dataSource->addField('timestamp', 'time', ['comparison' => 'gt']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T22:02:16+0200')]);

        $this->assertCount(2, $result);
    }

    public function testFilterByTimeGte(): void
    {
        $this->dataSource->addField('timestamp', 'time', ['comparison' => 'gte']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T22:02:16+0200')]);

        $this->assertCount(3, $result);
    }

    public function testFilterByTimeLt(): void
    {
        $this->dataSource->addField('timestamp', 'time', ['comparison' => 'lt']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T22:02:16+0200')]);

        $this->assertCount(7, $result);
    }

    public function testFilterByTimeLte(): void
    {
        $this->dataSource->addField('timestamp', 'time', ['comparison' => 'lte']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T22:02:16+0200')]);

        $this->assertCount(8, $result);
    }

    public function testFilterByTimeBetween(): void
    {
        $this->dataSource->addField('timestamp', 'time', ['comparison' => 'between']);
        $result = $this->filterDataSource(
            [
                'timestamp' => [
                    'from' => new \DateTime('T14:10:16+0200'),
                    'to' => new \DateTime('T17:07:16+0200'),
                ]
            ]
        );

        $this->assertCount(3, $result);
    }
}
