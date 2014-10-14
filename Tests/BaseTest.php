<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Driver\DriverFactoryManager;
use FSi\Component\DataSource\Driver\Elastica\DriverFactory;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\CoreDriverExtension;
use FSi\Component\DataSource\Driver\Elastica\Extension\Indexing\IndexingDriverExtension;
use FSi\Component\DataSource\Driver\Elastica\Extension\Ordering\OrderingDriverExtension;
use FSi\Component\DataSource\Extension\Symfony;
use FSi\Component\DataSource\Extension\Core;
use FSi\Component\DataSource\Extension\Core\Ordering\OrderingExtension;
use FSi\Component\DataSource\DataSourceFactory as BaseDataSourceFactory;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Component\DataSource\DataSource
     */
    protected $dataSource;

    /**
     * Return configured DataSourceFactory.
     *
     * @return \FSi\Component\DataSource\DataSourceFactory
     */
    protected function getDataSourceFactory()
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

    protected function filterDataSource($parameters)
    {
        $this->dataSource->bindParameters(
            $this->parametersEnvelope($parameters)
        );

        return $this->dataSource->getResult();
    }

    protected function parametersEnvelope(array $parameters)
    {
        return array(
            $this->dataSource->getName() => array(
                DataSourceInterface::PARAMETER_FIELDS => $parameters,
            ),
        );
    }
}
