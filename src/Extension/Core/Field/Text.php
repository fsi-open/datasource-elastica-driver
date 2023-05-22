<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use FSi\Component\DataSource\Field\FieldInterface;
use FSi\Component\DataSource\Field\Type\TextTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Text extends AbstractField implements TextTypeInterface
{
    public function buildQuery(BoolQuery $query, BoolQuery $filter, FieldInterface $field): void
    {
        $data = $field->getParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $fieldPath = $field->getOption('field');
        if (is_array($fieldPath)) {
            $match = new Query\MultiMatch();
            $match->setFields($fieldPath);
            $match->setQuery($data);
            $match->setOperator($field->getOption('operator'));
            $match->setParam('lenient', $field->getOption('lenient'));
        } else {
            $match = new Query\MatchQuery();
            $match->setFieldQuery($fieldPath, $data);
            $match->setFieldOperator($fieldPath, $field->getOption('operator'));
        }

        $query->addMust($match);
    }

    public function getId(): string
    {
        return 'text';
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        parent::initOptions($optionsResolver);

        $optionsResolver
            ->setAllowedValues('comparison', ['match'])
            ->setDefaults(['operator' => 'or'])
            ->setAllowedTypes('field', ['array', 'string', 'null'])
            ->setAllowedValues('operator', ['or', 'and'])
            ->setDefaults(['lenient' => false])
            ->setAllowedTypes('lenient', 'bool')
        ;
    }
}
