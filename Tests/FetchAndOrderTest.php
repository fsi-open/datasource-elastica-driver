<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Countable;
use Elastica\Result;
use FSi\Component\DataSource\Driver\Elastica\ElasticaResult;
use FSi\Component\DataSource\Extension\Ordering\OrderingExtension;

class FetchAndOrderTest extends BaseTest
{
    public function setUp(): void
    {
        $this->dataSource = $this->prepareIndex('test_index', [
            'surname' => ['type' => 'text', 'fielddata' => true],
        ]);
        $this->dataSource
            ->addField('surname', 'text', ['comparison' => 'match'])
            ->addField('active', 'boolean', ['comparison' => 'eq'])
            ->addField('salary', 'number', ['comparison' => 'gte'])
            ->addField('about', 'text', ['comparison' => 'match'])
        ;
    }

    public function testFetchingAllResults(): void
    {
        $this->assertCount(11, $this->dataSource->getResult());
    }

    public function testFetchingPaginatedResults(): void
    {
        $this->dataSource->setMaxResults(5);
        $results = $this->dataSource->getResult();
        $this->assertInstanceOf(Countable::class, $results);

        $this->assertCount(11, $results);

        $pageResultCount = 0;
        foreach ($results as $result) {
            $pageResultCount++;
        }

        $this->assertEquals(5, $pageResultCount);
    }

    public function testCombineMultipleFilters(): void
    {
        $this->dataSource->bindParameters(
            $this->parametersEnvelope(
                [
                    'about' => 'lorem',
                    'active' => false,
                    'salary' => 222222
                ]
            )
        );
        $result = $this->dataSource->getResult();

        $this->assertCount(2, $result);
    }

    public function testOrdering(): void
    {
        $this->dataSource->setMaxResults(20);
        $this->dataSource->bindParameters(
            [
                $this->dataSource->getName() => [
                    OrderingExtension::PARAMETER_SORT => [
                        'salary' => 'asc',
                        'surname' => 'asc'
                    ],
                ],
            ]
        );

        /** @var ElasticaResult<Result> $result */
        $result = $this->dataSource->getResult();

        $this->assertCount(11, $result);

        $expectedIds = ['p6', 'p10', 'p5', 'p8', 'p7', 'p11', 'p9', 'p1', 'p3', 'p2', 'p4'];
        $actualIds = [];
        foreach ($result as $single) {
            $actualIds[] = $single->getId();
        }

        $this->assertEquals($expectedIds, $actualIds);
    }
}
