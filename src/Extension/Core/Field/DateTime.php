<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Filter\AbstractMulti;
use Elastica\Filter\Range;
use Elastica\Query;
use Elastica\Query\Bool;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException;
use FSi\Component\DataSource\Driver\Elastica\DriverException;
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;

class DateTime extends AbstractField implements ElasticaFieldInterface
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq', 'lt', 'lte', 'gt', 'gte', 'between');

    protected function getFormat()
    {
        return \DateTime::ISO8601;
    }

    public function buildQuery(Bool $query, AbstractMulti $filter)
    {
        $data = $this->getCleanParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $fieldPath = $this->getField();

        if ($this->getComparison() == 'eq') {
            $formattedDate = $data->format($this->getFormat());
            $filter->addFilter(
                new Range(
                    $fieldPath,
                    array(
                        'gte' => $formattedDate,
                        'lte' => $formattedDate
                    )
                )
            );
        } elseif (in_array($this->getComparison(), array('lt', 'lte', 'gt', 'gte'))) {
            $filter->addFilter(
                new Range(
                    $fieldPath,
                    array(
                        $this->getComparison() => $data->format($this->getFormat()),
                    )
                )
            );
        } elseif ($this->getComparison() == 'between') {
            if (!is_array($data)) {
                throw new \InvalidArgumentException();
            }

            list($from, $to) = $data;

            $filter->addFilter(
                new Range(
                    $fieldPath,
                    array(
                        'gte' => $from->format($this->getFormat()),
                        'lte' => $to->format($this->getFormat()),
                    )
                )
            );
        } else {
            throw new DriverException(sprintf('Unexpected comparison type ("%s").', $this->getComparison()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'datetime';
    }
}
