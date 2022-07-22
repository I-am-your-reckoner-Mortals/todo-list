<?php

namespace App\Doctrine\Filters;

use App\Entity\Task;
use App\Services\AbstractFilterBuilder;
use App\Services\FilterBuilderInterface;
use Doctrine\ORM\QueryBuilder;

class PriorityFilter extends AbstractFilterBuilder implements FilterBuilderInterface
{
    public function apply(QueryBuilder $queryBuilder, $value): void
    {
        $alias = $this->getRootAlias($queryBuilder);

        $priorityStart = min($value);
        $priorityEnd = max($value);

        $queryBuilder
            ->andWhere($queryBuilder->expr()->between(
                $alias . '.priority', ':priorityStart', ':priorityEnd')
            )
            ->setParameter('priorityStart', $priorityStart)
            ->setParameter('priorityEnd', $priorityEnd);
    }
}