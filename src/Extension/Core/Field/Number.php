<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;

class Number extends AbstractField implements ElasticaFieldInterface
{
    protected $comparisons = ['eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'between'];

    public function getType()
    {
        return 'number';
    }
}
