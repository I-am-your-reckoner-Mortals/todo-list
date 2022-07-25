<?php

namespace App\Doctrine\Filters;

use App\Services\AbstractFilterBuilder;
use App\Services\FilterBuilderInterface;
use Doctrine\ORM\QueryBuilder;

class TitleFilter extends AbstractFilterBuilder implements FilterBuilderInterface
{
    public function apply(QueryBuilder $queryBuilder, $value): void
    {
        $alias = $this->getRootAlias($queryBuilder);

        if (!empty($value)) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like($alias . '.title', ':title'))
                ->setParameter('title', '%' . $value . '%');
        }
    }
}