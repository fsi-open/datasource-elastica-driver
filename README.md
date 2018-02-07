# DataSource Elastica Driver

Experimental DataSource Driver for ElasticSearch

## Requirements
This driver requires ES ^2.0

## Installation for Symfony Application

```sh
composer require fsi/datasource-elastica-driver
```

Service definition (`elastica-driver.xml`):

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service id="datasource.driver.factory.manager" class="%datasource.driver.factory.manager.class%">
            <argument type="collection">
                <argument type="service" id="datasource.driver.doctrine.factory" />
                <argument type="service" id="datasource.driver.collection.factory" />
                <argument type="service" id="datasource.driver.elastica.factory" />
            </argument>
        </service>

        <!-- DataSource Elastica Extensions -->
        <service id="datasource.driver.elastica.extension" class="FSi\Bundle\DataSourceBundle\DataSource\Extension\Symfony\DependencyInjection\Driver\DriverExtension">
            <argument type="string">elastica</argument>
            <!-- All services with tag "datasource.driver.elastica.field" are inserted here by DataSourcePass -->
            <argument type="collection" />
            <!-- All services with tag "datasource.driver.elastica.field.subscriber" are inserted here by DataSourcePass -->
            <argument type="collection" />
            <!-- All services with tag "datasource.driver.elastica.subscriber" are inserted here by DataSourcePass -->
            <argument type="collection" />
            <tag name="datasource.driver.extension" alias="elastica" />
        </service>

        <!-- DataSource Elastica Factory -->
        <service id="datasource.driver.elastica.factory" class="FSi\Component\DataSource\Driver\Elastica\ElasticaDriverFactory">
            <argument type="collection">
                <!--
                We don't need to be able to add more extensions.
                 * more fields can be registered with the datasource.driver.elastica.field tag
                 * more field subscribers can be registered with the datasource.driver.elastica.field.subscriber tag
                 * more listeners can be registered with the datasource.listener tag
                -->
                <argument type="service" id="datasource.driver.elastica.extension" />
            </argument>
            <tag name="datasource.driver.factory"/>
        </service>

        <!-- DataSource Elastica CoreExtensions -->
        <service id="datasource.driver.elastica.field.date" class="FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Date">
            <tag name="datasource.driver.elastica.field" alias="date" />
        </service>
        <service id="datasource.driver.elastica.field.datetime" class="FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\DateTime">
            <tag name="datasource.driver.elastica.field" alias="datetime" />
        </service>
        <service id="datasource.driver.elastica.field.entity" class="FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Entity">
            <tag name="datasource.driver.elastica.field" alias="entity" />
        </service>
        <service id="datasource.driver.elastica.field.number" class="FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Number">
            <tag name="datasource.driver.elastica.field" alias="number" />
        </service>
        <service id="datasource.driver.elastica.field.text" class="FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Text">
            <tag name="datasource.driver.elastica.field" alias="text" />
        </service>
        <service id="datasource.driver.elastica.field.time" class="FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Time">
            <tag name="datasource.driver.elastica.field" alias="time" />
        </service>
        <service id="datasource.driver.elastica.field.boolean" class="FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Boolean">
            <tag name="datasource.driver.elastica.field" alias="boolean" />
        </service>

        <!-- OrderingExtension -->
        <service id="datasource.driver.elastica.subscriber.ordering" class="FSi\Component\DataSource\Driver\Elastica\Extension\Ordering\OrderingDriverExtension">
            <tag name="datasource.driver.elastica.subscriber" alias="ordering" />
        </service>
        <service id="datasource.driver.elastica.field.subscriber.ordering" class="FSi\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension">
            <tag name="datasource.driver.elastica.field.subscriber" alias="ordering" />
        </service>
        
        <!-- OPTIONAL Indexing Extension -->
        <service id="datasource.driver.elastica.subscriber.indexing" class="FSi\Component\DataSource\Driver\Elastica\Extension\Indexing\IndexingDriverExtension">
            <tag name="datasource.driver.elastica.subscriber" alias="indexing" />
        </service>

        <!-- Symfony/FormExtension -->
        <service id="datasource.driver.elastica.field.subscriber.symfonyform" class="FSi\Bundle\DataSourceBundle\DataSource\Extension\Symfony\Form\Field\FormFieldExtension">
            <tag name="datasource.driver.elastica.field.subscriber" alias="symfonyform" />
            <argument type="service" id="form.factory" />
            <argument type="service" id="translator" />
        </service>
    </services>
</container>
```

```php
/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FsiDemoExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $xmlLoader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $xmlLoader->load('elastica-driver.xml');
    }
}
```

## Usage

```php
$dataSource = $this->dataSourceFactory->createDataSource('elastica', [
    'searchable' => $elasticaType, // instance of \Elastica\SearchableInterface
    'query' => null,
    'filter' => null,
    'master_query' => null,
], 'datasource_id');

```
