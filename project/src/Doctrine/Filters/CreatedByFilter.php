<?php

namespace App\Doctrine\Filters;

use App\Services\AbstractFilterBuilder;
use App\Services\FilterBuilderInterface;
use Doctrine\ORM\QueryBuilder;

class CreatedByFilter extends AbstractFilterBuilder implements FilterBuilderInterface
{
    public function apply(QueryBuilder $queryBuilder, $value): void
    {
        $alias = $this->getRootAlias($queryBuilder);

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq($alias . '.createdBy', ':user'))
            ->setParameter('user', $value);
    }
}