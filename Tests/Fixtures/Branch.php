<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
