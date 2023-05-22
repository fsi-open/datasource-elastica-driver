<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica;

use Elastica\Query\BoolQuery;
use FSi\Component\DataSource\Field\FieldInterface;
use FSi\Component\DataSource\Field\Type\FieldTypeInterface as CoreFieldTypeInterface;

interface FieldTypeInterface extends CoreFieldTypeInterface
{
    public function buildQuery(BoolQuery $query, BoolQuery $filter, FieldInterface $field): void;
}
