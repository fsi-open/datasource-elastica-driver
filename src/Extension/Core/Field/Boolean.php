<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Filter\AbstractMulti;
use Elastica\Filter\Term;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;

class Boolean extends AbstractField implements ElasticaFieldInterface
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq');

    /**
     * {@inheritdoc}
     */
    public function buildQuery(BoolQuery $query, AbstractMulti $filter)
    {
        $data = $this->getCleanParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $termFilter = new Term();
        $termFilter->setTerm($this->getField(), $data);

        $filter->addFilter($termFilter);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'boolean';
    }
}
