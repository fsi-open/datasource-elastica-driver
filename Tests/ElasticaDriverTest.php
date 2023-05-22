<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use Elastica\SearchableInterface;
use FSi\Component\DataSource\Driver\Elastica\ElasticaDriver;
use FSi\Component\DataSource\Driver\Elastica\FieldTypeInterface as ElasticaFieldTypeInterfaceAlias;
use FSi\Component\DataSource\Field\Type\FieldTypeInterface as DataSourceFieldTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElasticaDriverTest extends BaseTest
{
    public function testDriverHasExtensions(): void
    {
        $driver = $this->getElasticaFactory()
            ->createDriver(['searchable' => $this->createMock(SearchableInterface::class)]);

        $this->assertTrue($driver->hasFieldType('text'));
        $this->assertTrue($driver->hasFieldType('number'));
        $this->assertTrue($driver->hasFieldType('entity'));
        $this->assertTrue($driver->hasFieldType('date'));
        $this->assertTrue($driver->hasFieldType('time'));
        $this->assertTrue($driver->hasFieldType('datetime'));
        $this->assertTrue($driver->hasFieldType('boolean'));

        $this->assertFalse($driver->hasFieldType('unknown-field'));
    }

    public function fieldNameProvider(): array
    {
        return [
            ['text'],
            ['number'],
            ['entity'],
            ['date'],
            ['time'],
            ['datetime'],
            ['boolean']
        ];
    }

    /**
     * @dataProvider fieldNameProvider
     */
    public function testFields(string $fieldName): void
    {
        $driver = $this->getElasticaFactory()
            ->createDriver(['searchable' => $this->createMock(SearchableInterface::class)]);

        $this->assertTrue($driver->hasFieldType($fieldName));

        $field = $driver->getFieldType($fieldName);
        $this->assertInstanceOf(ElasticaFieldTypeInterfaceAlias::class, $field);

        $optionsResolver = new OptionsResolver();
        $field->initOptions($optionsResolver);
        $this->assertTrue($optionsResolver->isDefined('name'));
        $this->assertTrue($optionsResolver->isDefined('field'));
        $this->assertTrue($optionsResolver->isDefined('comparison'));
    }
}
