# DataSource Elastica Driver

Experimental DataSource Driver for ElasticSearch

## Use in Symfony2 Application

Service definition (`elastica-driver.xml`):

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <parameters>
        <parameter key="datasource.driver.extension.class">FSi\Component\DataSource\Extension\Symfony\DependencyInjection\Driver\DriverExtension</parameter>
        <parameter key="datasource.driver.elastica.factory.class">FSi\Component\DataSource\Driver\Elastica\DriverFactory</parameter>
    </parameters>

    <services>
        <service id="datasource.driver.factory.manager" class="%datasource.driver.factory.manager.class%">
            <argument type="collection">
                <argument type="service" id="datasource.driver.doctrine.factory" />
                <argument type="service" id="datasource.driver.collection.factory" />
                <argument type="service" id="datasource.driver.elastica.factory" />
            </argument>
        </service>

        <!-- DataSource Elastica Extensions -->
        <service id="datasource.driver.elastica.extension" class="%datasource.driver.extension.class%">
            <argument type="service" id="service_container" />
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
        <service id="datasource.driver.elastica.factory" class="%datasource.driver.elastica.factory.class%">
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

        <service id="datasource.driver.elastica.subscriber.result_indexer"
                 class="FSi\Bundle\DataSourceBundle\DataSource\Extension\Elastica\ElasticaToModelResultIndexer">
            <tag name="datasource.driver.elastica.subscriber" alias="result_indexer" />
        </service>

        <!-- OrderingExtension -->
        <service id="datasource.driver.elastica.subscriber.ordering" class="FSi\Component\DataSource\Driver\Elastica\Extension\Ordering\OrderingDriverExtension">
            <tag name="datasource.driver.elastica.subscriber" alias="ordering" />
        </service>
        <service id="datasource.driver.elastica.field.subscriber.ordering" class="FSi\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension">
            <tag name="datasource.driver.elastica.field.subscriber" alias="ordering" />
        </service>

        <!-- Symfony/FormExtension -->
        <service id="datasource.driver.elastica.field.subscriber.symfonyform" class="FSi\Component\DataSource\Extension\Symfony\Form\Field\FormFieldExtension">
            <tag name="datasource.driver.elastica.field.subscriber" alias="symfonyform" />
            <argument type="service" id="form.factory" />
        </service>
        <service id="datasource.driver.elastica.field.subscriber.symfony_null_form" class="FSi\Bundle\DataSourceBundle\DataSource\Extension\Symfony\Form\Field\FormFieldExtension">
            <tag name="datasource.driver.elastica.field.subscriber" alias="symfony_null_form" />
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

Register Results Indexer

```yml
xxx.datasource.elastica_driver.transformer_proxy:
    class: FSi\Bundle\XxxBundle\DataSource\TransformerProxy
    arguments: [@fos_elastica.elastica_to_model_transformer.collection.mnk_crm]

xxx.datasource.elastica_driver.result_indexer:
    class: FSi\Component\DataSource\Driver\Elastica\Extension\Transformation\TransformationDriverExtension
    arguments: [@xxx.datasource.elastica_driver.transformer_proxy]
    tags:
        - {name: datasource.driver.elastica.subscriber, alias: result_indexer}
```

Result Indexer

```php
<?php
namespace FSi\Bundle\XxxBundle\DataSource;

use FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;
use FSi\Component\DataSource\Driver\Elastica\TransformerInterface;

class TransformerProxy implements TransformerInterface
{
    /**
     * @var \FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface
     */
    private $transformer;

    public function __construct(ElasticaToModelTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function transform(array $objects)
    {
        return $this->transformer->transform($objects);
    }
}
```
