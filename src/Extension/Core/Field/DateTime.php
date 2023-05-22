<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use DateTimeInterface;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use Elastica\Query\Range;
use FSi\Component\DataSource\Driver\Elastica\Exception\ElasticaDriverException;
use FSi\Component\DataSource\Field\FieldInterface;
use FSi\Component\DataSource\Field\Type\DateTimeTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTime extends AbstractField implements DateTimeTypeInterface
{
    public function buildQuery(BoolQuery $query, BoolQuery $filter, FieldInterface $field): void
    {
        $data = $field->getParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $fieldPath = $field->getOption('field');

        if ($field->getOption('comparison') === 'eq') {
            $formattedDate = $data->format($this->getFormat());
            $filter->addMust(
                new Range(
                    $fieldPath,
                    ['gte' => $formattedDate, 'lte' => $formattedDate]
                )
            );
        } elseif (in_array($field->getOption('comparison'), ['lt', 'lte', 'gt', 'gte'])) {
            $filter->addMust(
                new Range(
                    $fieldPath,
                    [$field->getOption('comparison') => $data->format($this->getFormat())]
                )
            );
        } elseif ($field->getOption('comparison') === 'between') {
            if (!is_array($data)) {
                throw new \InvalidArgumentException();
            }

            if (!empty($data['from'])) {
                $filter->addMust(
                    new Range(
                        $fieldPath,
                        ['gte' => $data['from']->format($this->getFormat())]
                    )
                );
            }

            if (!empty($data['to'])) {
                $filter->addMust(
                    new Range(
                        $fieldPath,
                        ['lte' => $data['to']->format($this->getFormat())]
                    )
                );
            }
        } elseif ($field->getOption('comparison') === 'isNull') {
            $existsQuery = new Exists($fieldPath);
            if ('null' === $data) {
                $filter->addMustNot($existsQuery);
            } elseif ($data === 'no_null') {
                $filter->addMust($existsQuery);
            }
        } else {
            throw new ElasticaDriverException("Unexpected comparison type \"{$field->getOption('comparison')}\".");
        }
    }

    public function getId(): string
    {
        return 'datetime';
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        parent::initOptions($optionsResolver);

        $optionsResolver->setAllowedValues('comparison', ['eq', 'lt', 'lte', 'gt', 'gte', 'between', 'isNull']);
    }

    protected function getFormat(): string
    {
        return DateTimeInterface::ATOM;
    }
}
