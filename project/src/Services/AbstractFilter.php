<?php


namespace App\Services;


abstract class AbstractFilter
{
    /**
     * @var FilterBuilderInterface[]
    */
    protected array $filters = [];

    public function addFilter(string $name, FilterBuilderInterface $filterBuilderInterface)
    {
        $this->filters[$name] = $filterBuilderInterface;
    }
}