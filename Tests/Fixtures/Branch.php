<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures;

class Branch
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
