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
use Elastica\Query\Exists;
use Elastica\Query\Terms;
use FSi\Component\DataSource\Field\FieldInterface;
use FSi\Component\DataSource\Field\Type\EntityTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Entity extends AbstractField implements EntityTypeInterface
{
    public function buildQuery(BoolQuery $query, BoolQuery $filter, FieldInterface $field): void
    {
        $data = $field->getParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $fieldPath = $field->getOption('field');
        $comparison = $field->getOption('comparison');
        if ('eq' === $comparison) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $idFieldName = $field->getOption('identifier_field');

            $filter->addMust(
                new Terms(
                    sprintf("%s.%s", $fieldPath, $idFieldName),
                    [$accessor->getValue($data, $idFieldName)]
                )
            );
        } elseif ('isNull' === $comparison) {
            $existsQuery = new Exists($fieldPath);
            if ('null' === $data) {
                $filter->addMustNot($existsQuery);
            } elseif ($data === 'no_null') {
                $filter->addMust($existsQuery);
            }

        }
    }

    public function getId(): string
    {
        return 'entity';
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        parent::initOptions($optionsResolver);

        $optionsResolver
            ->setAllowedValues('comparison', ['eq', 'isNull'])
            ->setDefaults(['identifier_field' => 'id'])
            ->setAllowedTypes('identifier_field', ['string'])
        ;
    }
}
