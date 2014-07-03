<?php

namespace FSi\Component\DataSource\Driver\Elastica;

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

        return new Driver($this->extensions, $options['searchable']);
    }

    private function initOptions()
    {
        $this->optionsResolver->setDefaults(
            array(
                'searchable' => null,
            )
        );

        $this->optionsResolver->setAllowedTypes(
            array(
                'searchable' => array('\Elastica\SearchableInterface'),
            )
        );
    }
}
