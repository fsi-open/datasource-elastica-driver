<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Elastica\Client;
use FSi\Component\DataSource\Driver\DriverFactoryManager;
use FSi\Component\DataSource\Driver\Elastica\DriverFactory;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\CoreDriverExtension;
use FSi\Component\DataSource\Driver\Elastica\Extension\Indexing\IndexingDriverExtension;
use FSi\Component\DataSource\Driver\Elastica\Extension\Ordering\OrderingDriverExtension;
use FSi\Component\DataSource\Extension\Symfony;
use FSi\Component\DataSource\Extension\Core;
use FSi\Component\DataSource\Extension\Core\Ordering\OrderingExtension;
use FSi\Component\DataSource\DataSourceFactory as BaseDataSourceFactory;

class DataSourceFactory
{
    /**
     * Return configured DataSourceFactory.
     *
     * @return \FSi\Component\DataSource\DataSourceFactory
     */
    public function getDataSourceFactory()
    {
        $driverExtensions = array(
            new CoreDriverExtension(),
            new OrderingDriverExtension(),
            new IndexingDriverExtension(),
        );

        $driverFactoryManager = new DriverFactoryManager(
            array(
                new DriverFactory($driverExtensions)
            )
        );

        $dataSourceExtensions = array(
            new Symfony\Core\CoreExtension(),
            new Core\Pagination\PaginationExtension(),
            new OrderingExtension()
        );

        return new BaseDataSourceFactory($driverFactoryManager, $dataSourceExtensions);
    }
}
