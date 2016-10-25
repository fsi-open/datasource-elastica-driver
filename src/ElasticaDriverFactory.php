<?php

namespace FSi\Component\DataSource\Driver\Elastica;

use FSi\Component\DataSource\Driver\DriverFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElasticaDriverFactory implements DriverFactoryInterface
{
    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var array
     */
    private $extensions;

    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
        $this->optionsResolver = new OptionsResolver();

        $this->initOptions();
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

        return new ElasticaDriver(
            $this->extensions,
            $options['searchable'],
            $options['query'],
            $options['filter'],
            $options['master_query']
        );
    }

    private function initOptions()
    {
        $this->optionsResolver->setDefaults(
            array(
                'searchable' => null,
                'query' => null,
                'filter' => null,
                'master_query' => null,
            )
        );

        $this->optionsResolver->setAllowedTypes('searchable', array('\Elastica\SearchableInterface'));
        $this->optionsResolver->setAllowedTypes('query', array('null', '\Elastica\Query\AbstractQuery'));
        $this->optionsResolver->setAllowedTypes('filter', array('null', '\Elastica\Query\AbstractQuery'));
        $this->optionsResolver->setAllowedTypes('master_query', array('null', '\Elastica\Query'));
    }
}
