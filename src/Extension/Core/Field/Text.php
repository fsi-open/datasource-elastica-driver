<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Filter\AbstractMulti;
use Elastica\Query;
use Elastica\Query\Bool;
use FSi\Component\DataSource\Driver\Elastica\FieldInterface;

class Text extends AbstractField implements FieldInterface
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('like');

    public function buildQuery(Bool $query, AbstractMulti $filter)
    {
        $data = $this->getCleanParameter();
        if ($data === array() || $data === '' || $data === null) {
            return;
        }

        $match = new Query\Match();
        $match->setField($this->getField(), $data);

        $query->addMust($match);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'text';
    }
}
