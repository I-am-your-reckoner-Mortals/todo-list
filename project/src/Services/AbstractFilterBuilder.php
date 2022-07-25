<?php


namespace App\Services;


use Doctrine\ORM\QueryBuilder;

abstract class AbstractFilterBuilder
{
    protected function getRootAlias(QueryBuilder $queryBuilder): string
    {
        return $queryBuilder->getRootAliases()[0];
    }
}