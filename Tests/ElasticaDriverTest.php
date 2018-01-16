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
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\CoreDriverExtension;
use FSi\Component\DataSource\Field\FieldTypeInterface;
use PHPUnit\Framework\TestCase;

class ElasticaDriverTest extends TestCase
{
    public function testDriverHasExtensions()
    {
        $driver = new ElasticaDriver(
            [new CoreDriverExtension()],
            $this->createMock(SearchableInterface::class)
        );

        $this->assertTrue($driver->hasFieldType('text'));
        $this->assertTrue($driver->hasFieldType('number'));
        $this->assertTrue($driver->hasFieldType('entity'));
        $this->assertTrue($driver->hasFieldType('date'));
        $this->assertTrue($driver->hasFieldType('time'));
        $this->assertTrue($driver->hasFieldType('datetime'));
        $this->assertTrue($driver->hasFieldType('boolean'));

        $this->assertFalse($driver->hasFieldType('unknown-field'));
    }

    public function fieldNameProvider()
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
    public function testFields(string $fieldName)
    {
        $driver = new ElasticaDriver(
            [new CoreDriverExtension()],
            $this->createMock(SearchableInterface::class)
        );

        $this->assertTrue($driver->hasFieldType($fieldName));

        /** @var FieldTypeInterface $field */
        $field = $driver->getFieldType($fieldName);
        $this->assertTrue($field instanceof FieldTypeInterface);
        $this->assertTrue($field instanceof ElasticaFieldInterface);

        $comparisons = $field->getAvailableComparisons();
        $this->assertGreaterThan(0, count($comparisons));
    }
}
