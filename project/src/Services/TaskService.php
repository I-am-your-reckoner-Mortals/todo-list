<?php

namespace App\Services;

use App\Entity\Task;
use App\Entity\User;
use App\Validator\TaskValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class TaskService
{
    private WorkflowInterface $taskStateMachine;
    private EntityManagerInterface $entityManager;
    private TaskValidator $taskValidator;

    public function __construct(
        WorkflowInterface $taskStateMachine,
        EntityManagerInterface $entityManager,
        TaskValidator $taskValidator
    ) {
        $this->taskStateMachine = $taskStateMachine;
        $this->entityManager = $entityManager;
        $this->taskValidator = $taskValidator;
    }

    public function create(
        Task $task,
        User $createdBy,
        Task $parentTask = null
    ): Task {
        if ($parentTask != null) {
            $task->setParentTask($parentTask);
        }

        $task->setCreatedBy($createdBy);

        $this->taskValidator->validate($task);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function edit(
        Task $task,
        User $createdBy
    ): void {
        $task->setCreatedBy($createdBy);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public  function delete(): void
    {

    }

    public function update(Task $task): void
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function getAllowedStatuses(Task $task): array
    {
        //get current status
        $current = array_keys($this->taskStateMachine->getMarking($task)->getPlaces())[0];
        $statuses[$current] = $current;

        $transitions = $this->taskStateMachine->getEnabledTransitions($task);

        foreach ($transitions as $transition ) {
            foreach ($transition->getTos() as $toState) {
                $statuses[$transition->getName()] = $toState;
            }
        }

        return $statuses;
    }
}