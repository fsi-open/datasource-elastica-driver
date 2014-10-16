<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Fixtures;

class Branch
{
    private $id;

    private $idx;

    public function __construct($id, $idx = null)
    {
        $this->id = $id;
        $this->idx = $idx;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdx()
    {
        return $this->idx;
    }
}
