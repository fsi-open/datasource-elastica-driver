<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core;

use FSi\Component\DataSource\Driver\DriverAbstractExtension;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Boolean;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Date;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\DateTime;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Entity;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Number;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Text;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field\Time;

class CoreDriverExtension extends DriverAbstractExtension
{

    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes()
    {
        return array('elastica');
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypes()
    {
        return array(
            new Boolean(),
            new Date(),
            new DateTime(),
            new Entity(),
            new Number(),
            new Text(),
            new Time(),
        );
    }
}
