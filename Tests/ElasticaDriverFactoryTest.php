<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Elastica\SearchableInterface;
use FSi\Component\DataSource\Driver\DriverFactoryInterface;
use FSi\Component\DataSource\Driver\Elastica\ElasticaDriver;
use FSi\Component\DataSource\Driver\Elastica\ElasticaDriverFactory;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

class ElasticaDriverFactoryTest extends TestCase
{
    public function testDriverCreation(): void
    {
        $factory = new ElasticaDriverFactory($this->createMock(EventDispatcherInterface::class), []);
        $this->assertInstanceOf(DriverFactoryInterface::class, $factory);

        $driver = $factory->createDriver(['searchable' => $this->createMock(SearchableInterface::class)]);
        $this->assertInstanceOf(ElasticaDriver::class, $driver);
    }
}
