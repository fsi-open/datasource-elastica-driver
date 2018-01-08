<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;

class Boolean extends AbstractField implements ElasticaFieldInterface
{
    protected $comparisons = ['eq'];

    public function buildQuery(BoolQuery $query, BoolQuery $filter)
    {
        $data = $this->getCleanParameter();
        if ($this->isEmpty($data)) {
            return;
        }

        $termFilter = new Term();
        $termFilter->setTerm($this->getField(), $data);

        $filter->addMust($termFilter);
    }

    public function getType()
    {
        return 'boolean';
    }
}
