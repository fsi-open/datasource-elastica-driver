<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures;

use FSi\Component\DataSource\Driver\Elastica\Extension\Transformation\TransformerInterface;

class Transformer implements TransformerInterface
{
    public function transform(array $objects)
    {
        return $objects;
    }
}
