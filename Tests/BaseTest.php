<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Closure;
use Elastica\Client;
use Elastica\Document;
use Elastica\Mapping;
use FSi\Component\DataSource\DataSource;
use FSi\Component\DataSource\DataSourceFactory as BaseDataSourceFactory;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Driver\DriverFactoryManager;
use FSi\Component\DataSource\Driver\Elastica\ElasticaDriverFactory;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\CoreDriverExtension;
use FSi\Component\DataSource\Driver\Elastica\Extension\Indexing\IndexingDriverExtension;
use FSi\Component\DataSource\Driver\Elastica\Extension\Ordering\OrderingDriverExtension;
use FSi\Component\DataSource\Extension\Core;
use FSi\Component\DataSource\Extension\Core\Ordering\OrderingExtension;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /**
     * @var DataSource
     */
    protected $dataSource;

    protected function getDataSourceFactory(): BaseDataSourceFactory
    {
        $driverExtensions = [
            new CoreDriverExtension(),
            new OrderingDriverExtension(),
            new IndexingDriverExtension(),
        ];

        $driverFactoryManager = new DriverFactoryManager([
            new ElasticaDriverFactory($driverExtensions)
        ]);

        $dataSourceExtensions = [
            new Core\Pagination\PaginationExtension(),
            new OrderingExtension()
        ];

        return new BaseDataSourceFactory($driverFactoryManager, $dataSourceExtensions);
    }

    protected function filterDataSource(array $parameters)
    {
        $this->dataSource->bindParameters(
            $this->parametersEnvelope($parameters)
        );

        return $this->dataSource->getResult();
    }

    protected function parametersEnvelope(array $parameters): array
    {
        return [
            $this->dataSource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => $parameters,
            ],
        ];
    }

    protected function prepareIndex(
        string $indexName,
        array $mapping = [],
        ?Closure $transform = null
    ): DataSourceInterface {
        $client  = new Client();
        $index = $client->getIndex($indexName);
        if ($index->exists()) {
            $index->delete();
        }
        $index->create();
        if (count($mapping) > 0) {
            $index->setMapping(new Mapping($mapping));
        }

        $documents = [];
        $fixtures = require(__DIR__ . '/Fixtures/documents.php');
        foreach ($fixtures as $id => $fixture) {
            if (null !== $transform) {
                $fixture = $transform($fixture);
            }
            $documents[] = new Document($id, $fixture);
        }
        $index->addDocuments($documents);
        $index->refresh();

        return $this->getDataSourceFactory()->createDataSource(
            'elastica',
            ['searchable' => $index]
        );
    }
}
