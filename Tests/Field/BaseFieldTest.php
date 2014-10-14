<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use Elastica\Client;
use Elastica\Document;
use FSi\Component\DataSource\Driver\Elastica\Tests\BaseTest;

abstract class BaseFieldTest extends BaseTest
{
    protected function prepareIndex($indexName, $typeName, $mapping = null, \Closure $transform = null)
    {
        $client  = new Client();
        $index = $client->getIndex($indexName);
        if ($index->exists()) {
            $index->delete();
        }
        $index->create();
        $type = $index->getType($typeName);
        if (null !== $mapping && is_array($mapping)) {
            $type->setMapping($mapping);
        }

        $documents = array();
        $fixtures = require(__DIR__ . '/../Fixtures/documents.php');
        foreach ($fixtures as $id => $fixture) {
            if (null !== $transform) {
                $fixture = $transform($fixture);
            }
            $documents[] = new Document($id, $fixture);
        }
        $type->addDocuments($documents);
        $index->flush(true);

        return $this->getDataSourceFactory()->createDataSource(
            'elastica',
            array('searchable' => $type)
        );
    }
}
