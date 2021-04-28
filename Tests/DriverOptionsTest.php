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

class DriverOptionsTest extends BaseTest
{
    public function testFieldOptionInField()
    {
        $this->prepareDataSource();

        $this->dataSource->clearFields();
        $this->dataSource->addField('branch', 'number', 'eq', ['field' => 'branch.id']);

        $result = $this->filterDataSource(['branch' => 2]);
        $this->assertEquals(2, count($result));
    }

    public function testUseUserProvidedQueryAndFilter()
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

        $this->assertEquals(2, count($result));
    }

    public function testUserProvidedMasterQuery()
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

        $this->assertTrue($result->hasAggregations());

        $expectedAgg = [
            'salary_agg' => [
                'value' => 669761
            ]
        ];
        $this->assertEquals($expectedAgg, $result->getAggregations());
    }

    private function prepareDataSource($matchQuery = null, $termFilter = null, $masterQuery = null)
    {
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
                'master_query' => $masterQuery,
                'query' => $matchQuery,
                'filter' => $termFilter,
            ]
        );

        $this->dataSource
            ->addField('name', 'text', 'match')
            ->addField('active', 'boolean', 'eq')
            ->addField('salary', 'number', 'gte')
            ->addField('about', 'text', 'match');
    }
}
