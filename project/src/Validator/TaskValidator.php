<?php

namespace App\Validator;

use App\Entity\Task;
use Symfony\Component\Workflow\WorkflowInterface;

class TaskValidator
{
    private WorkflowInterface $taskStateMachine;

    public function __construct(WorkflowInterface $taskStateMachine)
    {
        $this->taskStateMachine = $taskStateMachine;
    }

    public function validate(Task $task): bool
    {
        if (in_array($task->getPriority(), array_keys(Task::PRIORITIES))) {
            return false;
        }

        return true;
    }
}