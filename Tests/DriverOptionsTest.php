<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Elastica\Aggregation\Sum;
use Elastica\Client;
use Elastica\Query;
use Elastica\Filter\Term;
use Elastica\Query\Match;

class DriverOptionsTest extends BaseTest
{
    public function testUseUserProvidedQueryAndFilter()
    {
        $matchQuery = new Match();
        $matchQuery->setField('about', 'lorem');

        $termFilter = new Term();
        $termFilter->setTerm('active', true);

        $this->prepareDataSource($matchQuery, $termFilter);

        $this->dataSource->bindParameters(
            $this->parametersEnvelope(
                array(
                    'name' => 'Jan',
                    'salary' => 111111
                )
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

        $matchQuery = new Match();
        $matchQuery->setField('about', 'lorem');

        $termFilter = new Term();
        $termFilter->setTerm('active', true);

        $this->prepareDataSource($matchQuery, $termFilter, $masterQuery);

        $this->dataSource->bindParameters(
            $this->parametersEnvelope(
                array(
                    'name' => 'Jan',
                    'salary' => 111111
                )
            )
        );
        $result = $this->dataSource->getResult();

        $this->assertTrue($result->hasAggregations());

        $expectedAgg = array(
            'salary_agg' => array(
                'value' => 669761
            )
        );
        $this->assertEquals($expectedAgg, $result->getAggregations());
    }

    private function prepareDataSource($matchQuery = null, $termFilter = null, $masterQuery = null)
    {
        $client  = new Client();

        $dataSourceFactory = new DataSourceFactory();
        $this->dataSource = $dataSourceFactory->getDataSourceFactory()->createDataSource(
            'elastica',
            array(
                'searchable' => $client->getIndex('test_index')->getType('test_type'),
                'master_query' => $masterQuery,
                'query' => $matchQuery,
                'filter' => $termFilter,
            )
        );

        $this->dataSource
            ->addField('name', 'text', 'like')
            ->addField('active', 'boolean', 'eq')
            ->addField('salary', 'number', 'gte')
            ->addField('about', 'text', 'like');
    }
} 
