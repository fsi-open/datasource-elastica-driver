<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use FSi\Component\DataSource\Extension\Core\Ordering\OrderingExtension;

class FetchAndOrderTest extends BaseTest
{
    public function setUp()
    {
        $this->dataSource = $this->prepareIndex('test_index', 'test_type', [
            'surname' => ['type' => 'text', 'fielddata' => true],
        ]);
        $this->dataSource
            ->addField('surname', 'text', 'match')
            ->addField('active', 'boolean', 'eq')
            ->addField('salary', 'number', 'gte')
            ->addField('about', 'text', 'match');
    }

    public function testFetchingAllResults()
    {
        $this->assertEquals(11, count($this->dataSource->getResult()));
    }

    public function testFetchingPaginatedResults()
    {
        $this->dataSource->setMaxResults(5);
        $results = $this->dataSource->getResult();

        $this->assertEquals(11, count($results));

        $pageResultCount = 0;
        foreach ($results as $result) {
            $pageResultCount++;
        }

        $this->assertEquals(5, $pageResultCount);
    }

    public function testCombineMultipleFilters()
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

        $this->assertEquals(2, count($result));
    }

    public function testOrdering()
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

        $result = $this->dataSource->getResult();

        $this->assertEquals(11, count($result));

        $expectedIds = ['p6', 'p10', 'p5', 'p8', 'p7', 'p11', 'p9', 'p1', 'p3', 'p2', 'p4'];
        $actualIds = [];
        foreach ($result as $single) {
            /** @var \Elastica\Result $single */
            $actualIds[] = $single->getId();
        }

        $this->assertEquals($expectedIds, $actualIds);
    }
}
