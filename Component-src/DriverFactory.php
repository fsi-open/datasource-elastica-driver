<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Client;
use FSi\Component\DataSource\Driver\DriverFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DriverFactory implements DriverFactoryInterface
{
    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var array
     */
    private $extensions;

    /**
     * @var \Elastica\Client
     */
    private $client;

    public function __construct(array $extensions, Client $client)
    {
        $this->extensions = $extensions;
        $this->optionsResolver = new OptionsResolver();

        $this->initOptions();
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverType()
    {
        return 'elastica';
    }

    /**
     * {@inheritdoc}
     */
    public function createDriver($options = array())
    {
        $options = $this->optionsResolver->resolve($options);

        $type = $this->client->getIndex($options['index'])
            ->getType($options['type']);

        return new Driver($this->extensions, $type, $options['transformer']);
    }

    private function initOptions()
    {
        $this->optionsResolver->setDefaults(
            array(
                'index' => null,
                'type' => null,
                'transformer' => null,
            )
        );

        $this->optionsResolver->setAllowedTypes(
            array(
                'index' => array('string'),
                'type' => array('string'),
                'transformer' => array('null', 'object'),
            )
        );
    }
}
