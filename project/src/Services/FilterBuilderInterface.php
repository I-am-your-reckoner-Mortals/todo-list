<?php


namespace App\Services;


use Doctrine\ORM\QueryBuilder;

interface FilterBuilderInterface
{
    public function apply(QueryBuilder $queryBuilder, $value): void;
}