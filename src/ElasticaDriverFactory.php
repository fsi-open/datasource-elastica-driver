<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\SearchableInterface;
use FSi\Component\DataSource\Driver\DriverFactoryInterface;
use FSi\Component\DataSource\Driver\DriverInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElasticaDriverFactory implements DriverFactoryInterface
{
    private EventDispatcherInterface $eventDispatcher;
    /**
     * @var array<FieldTypeInterface>
     */
    private array $fieldTypes;
    private OptionsResolver $optionsResolver;

    public static function getDriverType(): string
    {
        return 'elastica';
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param array<FieldTypeInterface> $fieldTypes
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, array $fieldTypes)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->fieldTypes = $fieldTypes;
        $this->optionsResolver = new OptionsResolver();

        $this->initOptions();
    }

    public function createDriver($options = []): DriverInterface
    {
        $options = $this->optionsResolver->resolve($options);

        return new ElasticaDriver(
            $this->eventDispatcher,
            $this->fieldTypes,
            $options['searchable'],
            $options['query'],
            $options['filter'],
            $options['master_query']
        );
    }

    private function initOptions(): void
    {
        $this->optionsResolver->setDefaults([
            'searchable' => null,
            'query' => null,
            'filter' => null,
            'master_query' => null,
        ]);

        $this->optionsResolver->setAllowedTypes('searchable', [SearchableInterface::class]);
        $this->optionsResolver->setAllowedTypes('query', ['null', AbstractQuery::class]);
        $this->optionsResolver->setAllowedTypes('filter', ['null', AbstractQuery::class]);
        $this->optionsResolver->setAllowedTypes('master_query', ['null', Query::class]);
    }
}
