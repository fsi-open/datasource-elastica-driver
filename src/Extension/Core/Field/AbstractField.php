<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Query\Exists;
use FSi\Component\DataSource\Driver\Elastica\Exception\ElasticaDriverException;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use FSi\Component\DataSource\Driver\Elastica\FieldTypeInterface;
use FSi\Component\DataSource\Field\FieldInterface;
use FSi\Component\DataSource\Field\Type\AbstractFieldType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractField extends AbstractFieldType implements FieldTypeInterface
{
    public function buildQuery(BoolQuery $query, BoolQuery $filter, FieldInterface $field): void
    {
        $data = $field->getParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $fieldPath = $field->getOption('field');

        switch ($field->getOption('comparison')) {
            case 'eq':
                $termFilter = new Term();
                $termFilter->setTerm($fieldPath, $data);

                $filter->addMust($termFilter);
                break;
            case 'neq':
                $termFilter = new Term();
                $termFilter->setTerm($fieldPath, $data);

                $filter->addMustNot($termFilter);
                break;
            case 'between':
                if (!is_array($data)) {
                    throw new ElasticaDriverException("Between comparison needs an array");
                }
                $from = array_shift($data);
                $to = array_shift($data);
                $filter->addMust(new Range($fieldPath, ['gte' => $from, 'lte' => $to]));
                break;
            case 'lt':
            case 'lte':
            case 'gt':
            case 'gte':
                $filter->addMust(new Range($fieldPath, [$field->getOption('comparison') => $data]));
                break;
            case 'in':
                $filter->addMust(
                    new Terms($fieldPath, $data)
                );
                break;
            case 'notIn':
                if (!is_array($data)) {
                    throw new ElasticaDriverException();
                }
                $filter->addMustNot(new Terms($fieldPath, $data));
                break;
            case 'isNull':
                $existsQuery = new Exists($fieldPath);
                if ('null' === $data) {
                    $filter->addMustNot($existsQuery);
                } elseif ($data === 'no_null') {
                    $filter->addMust($existsQuery);
                }
                break;
            default:
                throw new ElasticaDriverException(
                    sprintf('Unexpected comparison type ("%s").', $field->getOption('comparison'))
                );
        }
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        parent::initOptions($optionsResolver);

        $optionsResolver
            ->setDefault('field', null)
            ->setAllowedTypes('field', ['string', 'null'])
            ->setNormalizer('field', function (Options $options, $value) {
                return $value ?? $options['name'];
            })
            ->setAllowedValues(
                'comparison',
                ['eq', 'neq', 'between', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'isNull']
            )
        ;
    }
}
