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
use FSi\Component\DataSource\Driver\DriverAbstract;
use FSi\Component\DataSource\Driver\DriverFactoryInterface;
use FSi\Component\DataSource\Driver\Elastica\ElasticaDriverFactory;
use PHPUnit\Framework\TestCase;

class ElasticaDriverFactoryTest extends TestCase
{
    public function testDriverCreation()
    {
        $factory = new ElasticaDriverFactory([]);
        $this->assertTrue($factory instanceof DriverFactoryInterface);

        $driver = $factory->createDriver(['searchable' => $this->createMock(SearchableInterface::class)]);
        $this->assertTrue($driver instanceof DriverAbstract);
    }
}
