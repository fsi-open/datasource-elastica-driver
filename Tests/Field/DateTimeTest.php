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

class DateTimeTest extends BaseTest
{
    public function setUp(): void
    {
        $this->dataSource = $this->prepareIndex('datetime_index');
    }

    public function testIsNullComparison(): void
    {
        $this->dataSource->addField('no_timestamp', 'datetime', ['field' => 'timestamp', 'comparison' => 'isNull']);

        $result = $this->filterDataSource(['no_timestamp' => 'null']);
        $this->assertCount(1, $result);

        $result = $this->filterDataSource(['no_timestamp' => 'no_null']);
        $this->assertCount(10, $result);
    }

    public function testFilterByEmptyParameter(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'eq']);

        $result = $this->filterDataSource(['timestamp' => '']);
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['timestamp' => null]);
        $this->assertCount(11, $result);

        $result = $this->filterDataSource(['timestamp' => []]);
        $this->assertCount(11, $result);
    }

    public function testFilterByDateTimeEq(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'eq']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('2014-06-02T22:02:16+0200')]);

        $this->assertCount(1, $result);
    }

    public function testFilterByDateTimeGt(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'gt']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('2014-06-01T23:01:16+0200')]);

        $this->assertCount(9, $result);
    }

    public function testFilterByDateTimeGte(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'gte']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('2014-06-09T15:09:16+0200')]);

        $this->assertCount(1, $result);
    }

    public function testFilterByDateTimeLt(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'lt']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('2014-06-02T22:02:16+0200')]);

        $this->assertCount(1, $result);
    }

    public function testFilterByDateTimeLte(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'lte']);
        $result = $this->filterDataSource(['timestamp' => new \DateTime('2014-06-02T22:02:16+0200')]);

        $this->assertCount(2, $result);
    }

    public function testFilterByDateTimeBetween(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'between']);
        $result = $this->filterDataSource(
            [
                'timestamp' => [
                    'from' => new \DateTime('2014-06-07T17:07:16+0200'),
                    'to' => new \DateTime('2014-06-10T14:10:16+0200'),
                ]
            ]
        );

        $this->assertCount(3, $result);
    }

    public function testFilterByDateTimeBetweenAcceptAssociativeArray(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'between']);
        $result = $this->filterDataSource(
            [
                'timestamp' => [
                    'from' => new \DateTime('2014-06-07T17:07:16+0200'),
                    'to' => new \DateTime('2014-06-10T14:10:16+0200'),
                ]
            ]
        );

        $this->assertCount(3, $result);
    }

    public function testFilterByDateTimeBetweenDiscardEmptyParameters(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'between']);
        $result = $this->filterDataSource(
            [
                'timestamp' => [
                    'from' => null,
                    'to' => null,
                ]
            ]
        );

        $this->assertCount(11, $result);
    }

    public function testFilterByDateTimeBetweenOnlyFromField(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'between']);
        $result = $this->filterDataSource(
            [
                'timestamp' => [
                    'from' => new \DateTime('2014-06-07T17:07:16+0200'),
                ]
            ]
        );

        $this->assertCount(3, $result);
    }

    public function testFilterByDateTimeBetweenOnlyToField(): void
    {
        $this->dataSource->addField('timestamp', 'datetime', ['comparison' => 'between']);
        $result = $this->filterDataSource(
            [
                'timestamp' => [
                    'to' => new \DateTime('2014-06-07T17:07:16+0200'),
                ]
            ]
        );

        $this->assertCount(8, $result);
    }
}
