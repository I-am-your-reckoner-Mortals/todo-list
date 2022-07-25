<?php

namespace App\Doctrine\Filters;

use App\Services\AbstractFilterBuilder;
use App\Services\FilterBuilderInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Workflow\WorkflowInterface;

class StatusFilter extends AbstractFilterBuilder implements FilterBuilderInterface
{
    private WorkflowInterface $taskStateMachine;

    public function __construct(WorkflowInterface $taskStateMachine)
    {
        $this->taskStateMachine = $taskStateMachine;
    }

    public function apply(QueryBuilder $queryBuilder, $value): void
    {

        if ($this->isValid($value)) {
            $alias = $this->getRootAlias($queryBuilder);

            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq($alias . '.status', ':status'))
                ->setParameter('status', $value);
        }
    }

    private function isValid(string $status): bool
    {
        if (in_array($status, $this->taskStateMachine->getDefinition()->getPlaces())) {
            return true;
        }

        return false;
    }
}