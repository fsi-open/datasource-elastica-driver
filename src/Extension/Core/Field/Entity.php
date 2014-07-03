<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Filter\AbstractMulti;
use Elastica\Filter;
use Elastica\Query\Bool;
use FSi\Component\DataSource\Driver\Elastica\FieldInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Entity extends AbstractField implements FieldInterface
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq');

    public function buildQuery(Bool $query, AbstractMulti $filter)
    {
        $data = $this->getCleanParameter();
        if ($data === array() || $data === '' || $data === null) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $idFieldName = $this->getOption('identifier_field');

        $filter->addFilter(
            new Filter\Terms(
                sprintf("%s.%s", $this->getName(), $idFieldName),
                array($accessor->getValue($data, $idFieldName))
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()
            //->setRequired(array('identifier_field'))
            ->setOptional(array('field', 'identifier_field'))
            ->setAllowedTypes(
                array(
                    'field' => array('string', 'null'),
                    'identifier_field' => array('string')
                )
            )
        ;
    }
}
