<?php

namespace App\Services;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class TaskService
{
    private WorkflowInterface $taskStateMachine;
    private EntityManagerInterface $entityManager;

    public function __construct(
        WorkflowInterface $taskStateMachine,
        EntityManagerInterface $entityManager
    ) {
        $this->taskStateMachine = $taskStateMachine;
        $this->entityManager = $entityManager;
    }

    public function create(
        Task $task,
        User $createdBy
    ): Task {
        $task->setCreatedBy($createdBy);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function edit(
        Task $task,
        User $createdBy
    ): Task {
        $task->setCreatedBy($createdBy);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public  function delete()
    {

    }

    public function createChild(Task $parentTask)
    {

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