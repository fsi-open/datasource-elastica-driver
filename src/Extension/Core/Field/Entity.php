<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Entity extends AbstractField implements ElasticaFieldInterface
{
    protected $comparisons = ['eq'];

    public function buildQuery(BoolQuery $query, BoolQuery $filter)
    {
        $data = $this->getCleanParameter();
        if (empty($data)) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $idFieldName = $this->getOption('identifier_field');

        $filter->addMust(
            new Terms(
                sprintf("%s.%s", $this->getField(), $idFieldName),
                [$accessor->getValue($data, $idFieldName)]
            )
        );
    }

    public function getType()
    {
        return 'entity';
    }

    public function initOptions()
    {
        parent::initOptions();

        $this->getOptionsResolver()
            ->setDefaults(['identifier_field' => 'id'])
            ->setAllowedTypes('identifier_field', ['string'])
        ;
    }
}
