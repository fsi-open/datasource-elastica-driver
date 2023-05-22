<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Elastica\Aggregation\Sum;
use Elastica\Client;
use Elastica\Document;
use Elastica\Query;
use Elastica\Query\MatchQuery;
use Elastica\Query\Term;
use FSi\Component\DataSource\Driver\Elastica\ElasticaResult;

class DriverOptionsTest extends BaseTest
{
    public function testFieldOptionInField(): void
    {
        $this->prepareDataSource();

        $this->dataSource->clearFields();
        $this->dataSource->addField('branch', 'number', ['field' => 'branch.id', 'comparison' => 'eq']);

        $result = $this->filterDataSource(['branch' => 2]);
        $this->assertCount(2, $result);
    }

    public function testUseUserProvidedQueryAndFilter(): void
    {
        $matchQuery = new MatchQuery();
        $matchQuery->setField('about', 'lorem');

        $termFilter = new Term();
        $termFilter->setTerm('active', true);

        $this->prepareDataSource($matchQuery, $termFilter);

        $this->dataSource->bindParameters(
            $this->parametersEnvelope(
                [
                    'name' => 'Jan',
                    'salary' => 111111
                ]
            )
        );
        $result = $this->dataSource->getResult();

        $this->assertCount(2, $result);
    }

    public function testUserProvidedMasterQuery(): void
    {
        $sumAggregation = new Sum('salary_agg');
        $sumAggregation->setField('salary');

        $masterQuery = new Query();
        $masterQuery->addAggregation($sumAggregation);

        $matchQuery = new MatchQuery();
        $matchQuery->setField('about', 'lorem');

        $termFilter = new Term();
        $termFilter->setTerm('active', true);

        $this->prepareDataSource($matchQuery, $termFilter, $masterQuery);

        $this->dataSource->bindParameters(
            $this->parametersEnvelope(
                [
                    'name' => 'Jan',
                    'salary' => 111111
                ]
            )
        );
        $result = $this->dataSource->getResult();
        $this->assertInstanceOf(ElasticaResult::class, $result);

        $this->assertTrue($result->hasAggregations());

        $expectedAgg = [
            'salary_agg' => [
                'value' => 669761
            ]
        ];
        $this->assertEquals($expectedAgg, $result->getAggregations());
    }

    private function prepareDataSource(
        ?Query\AbstractQuery $matchQuery = null,
        ?Query\AbstractQuery $termFilter = null,
        ?Query $masterQuery = null
    ): void {
        $client  = new Client();

        $index = $client->getIndex('test_index');
        if ($index->exists()) {
            $index->delete();
        }
        $index->create();

        $documents = [];
        $fixtures = require('Fixtures/documents.php');
        foreach ($fixtures as $id => $fixture) {
            $documents[] = new Document($id, $fixture);
        }
        $index->addDocuments($documents);
        $index->refresh();

        $this->dataSource = $this->getDataSourceFactory()->createDataSource(
            'elastica',
            [
                'searchable' => $index,
                'query' => $matchQuery,
                'filter' => $termFilter,
                'master_query' => $masterQuery,
            ]
        );

        $this->dataSource
            ->addField('name', 'text', ['comparison' => 'match'])
            ->addField('active', 'boolean', ['comparison' => 'eq'])
            ->addField('salary', 'number', ['comparison' => 'gte'])
            ->addField('about', 'text', ['comparison' => 'match'])
        ;
    }
}
