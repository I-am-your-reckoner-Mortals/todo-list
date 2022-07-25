<?php

namespace App\Doctrine\Filters;

use App\Services\AbstractFilterBuilder;
use App\Services\FilterBuilderInterface;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;

class Ordering extends AbstractFilterBuilder implements FilterBuilderInterface
{
    public function apply(QueryBuilder $queryBuilder, $value): void
    {
        $alias = $this->getRootAlias($queryBuilder);

        if ($this->isValid($value)) {
            $queryBuilder
                ->addOrderBy($alias . '.' . $value['order_field'], $value['order_type']);
        }
    }

    public function isValid(array $value): bool
    {
        foreach ($value as $item) {
            if ($item == null) {
                return false;
            }
        }
        return true;
    }
}