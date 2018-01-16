<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Indexing;

use Elastica\ResultSet;
use FSi\Component\DataSource\Driver\DriverAbstractExtension;
use FSi\Component\DataSource\Driver\Elastica\ElasticaResult;
use FSi\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent\ResultEventArgs;

class IndexingDriverExtension extends DriverAbstractExtension
{
    public function getExtendedDriverTypes()
    {
        return ['elastica'];
    }

    public static function getSubscribedEvents()
    {
        return [DriverEvents::POST_GET_RESULT => ['postGetResult', 1024]];
    }

    public function postGetResult(ResultEventArgs $event)
    {
        $result = $event->getResult();

        if ($result instanceof ResultSet) {
            $result = new ElasticaResult($result);
            $event->setResult($result);
        }
    }
}
