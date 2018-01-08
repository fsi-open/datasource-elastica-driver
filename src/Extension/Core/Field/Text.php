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
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;

class Text extends AbstractField implements ElasticaFieldInterface
{
    protected $comparisons = ['match'];

    public function buildQuery(BoolQuery $query, BoolQuery $filter)
    {
        $data = $this->getCleanParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $field = $this->getField();
        if (is_array($field)) {
            $match = new Query\MultiMatch();
            $match->setFields($field);
            $match->setQuery($data);
            $match->setOperator($this->getOption('operator'));
        } else {
            $match = new Query\Match();
            $match->setFieldQuery($field, $data);
            $match->setFieldOperator($field, $this->getOption('operator'));
        }

        $query->addMust($match);
    }

    public function getType()
    {
        return 'text';
    }

    public function initOptions()
    {
        parent::initOptions();

        $this->getOptionsResolver()
            ->setDefaults(['operator' => 'or'])
            ->setAllowedTypes('field', ['array', 'string', 'null'])
            ->setAllowedValues('operator', ['or', 'and'])
        ;
    }
}
