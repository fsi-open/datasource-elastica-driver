<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Filter\AbstractMulti;
use Elastica\Query;
use Elastica\Query\Bool;
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;

class Text extends AbstractField implements ElasticaFieldInterface
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('match');

    /**
     * {@inheritdoc}
     */
    public function buildQuery(Bool $query, AbstractMulti $filter)
    {
        $data = $this->getCleanParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $match = new Query\Match();
        $match->setFieldQuery($this->getField(), $data);
        $match->setFieldOperator($this->getField(), $this->getOption('operator'));

        $query->addMust($match);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        parent::initOptions();

        $this->getOptionsResolver()
            ->setDefaults(array('operator' => 'or'))
            ->addAllowedValues(
                array(
                    'operator' => array('or', 'and')
                )
            )
        ;
    }
}
