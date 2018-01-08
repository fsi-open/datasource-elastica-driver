<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
    public function getExtendedDriverTypes()
    {
        return ['elastica'];
    }

    protected function loadFieldTypes()
    {
        return [
            new Boolean(),
            new Date(),
            new DateTime(),
            new Entity(),
            new Number(),
            new Text(),
            new Time(),
        ];
    }
}
