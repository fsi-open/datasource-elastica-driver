<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use FSi\Component\DataSource\Field\Type\DateTypeInterface;

class Date extends DateTime implements DateTypeInterface
{
    public function getId(): string
    {
        return 'date';
    }

    protected function getFormat(): string
    {
        return 'Y-m-d';
    }
}
