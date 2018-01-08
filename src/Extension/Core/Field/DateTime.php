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
use Elastica\Query\Range;
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;
use FSi\Component\DataSource\Driver\Elastica\Exception\ElasticaDriverException;

class DateTime extends AbstractField implements ElasticaFieldInterface
{
    protected $comparisons = ['eq', 'lt', 'lte', 'gt', 'gte', 'between'];

    protected function getFormat()
    {
        return \DateTime::ISO8601;
    }

    public function buildQuery(BoolQuery $query, BoolQuery $filter)
    {
        $data = $this->getCleanParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $fieldPath = $this->getField();

        if ($this->getComparison() == 'eq') {
            $formattedDate = $data->format($this->getFormat());
            $filter->addMust(
                new Range(
                    $fieldPath,
                    ['gte' => $formattedDate, 'lte' => $formattedDate]
                )
            );
        } elseif (in_array($this->getComparison(), ['lt', 'lte', 'gt', 'gte'])) {
            $filter->addMust(
                new Range(
                    $fieldPath,
                    [$this->getComparison() => $data->format($this->getFormat())]
                )
            );
        } elseif ($this->getComparison() == 'between') {
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

        } else {
            throw new ElasticaDriverException(sprintf('Unexpected comparison type ("%s").', $this->getComparison()));
        }
    }

    public function getType()
    {
        return 'datetime';
    }
}
