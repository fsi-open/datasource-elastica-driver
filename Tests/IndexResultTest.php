<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Elastica\ResultSet;
use Elastica\SearchableInterface;
use FSi\Component\DataSource\DataSource;
use FSi\Component\DataSource\Driver\Elastica\ElasticaDriver;
use FSi\Component\DataSource\Driver\Elastica\ElasticaResult;
use FSi\Component\DataSource\Driver\Elastica\Extension\Indexing\IndexingDriverExtension;
use PHPUnit\Framework\TestCase;

class IndexResultTest extends TestCase
{
    public function testIndexResult()
    {
        $elasticaResultSet = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $searchable = $this->createMock(SearchableInterface::class);
        $searchable->expects($this->any())
            ->method('search')
            ->willReturn($elasticaResultSet);

        $datasource = new DataSource(
            new ElasticaDriver(
                [new IndexingDriverExtension()],
                $searchable
            ),
            'test'
        );

        $result = $datasource->getResult();

        $this->assertNotInstanceOf(ResultSet::class, $result);
        $this->assertInstanceOf(ElasticaResult::class, $result);
    }
}
