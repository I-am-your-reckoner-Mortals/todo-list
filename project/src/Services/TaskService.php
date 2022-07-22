<?php

namespace App\Services;

use App\Entity\Task;
use App\Entity\User;
use App\Models\TaskStatuses;
use App\Repository\TaskRepository;
use App\Validator\TaskValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use TaskDoneException;

class TaskService
{
    private WorkflowInterface $taskStateMachine;
    private EntityManagerInterface $entityManager;
    private TaskValidator $taskValidator;
    private TaskRepository $taskRepository;

    public function __construct(
        WorkflowInterface $taskStateMachine,
        EntityManagerInterface $entityManager,
        TaskValidator $taskValidator,
        TaskRepository $taskRepository
    ) {
        $this->taskStateMachine = $taskStateMachine;
        $this->entityManager = $entityManager;
        $this->taskValidator = $taskValidator;
        $this->taskRepository = $taskRepository;
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

    /**
     * @throws TaskDoneException
     */
    public  function delete(Task $task): void
    {
        if ($task->getStatus() === TaskStatuses::DONE) {
            throw new TaskDoneException();
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function isChildComplete(Task $task): bool
    {

        foreach ($this->taskRepository->findChildTasks($task) as $childTask) {
            if ($childTask->getStatus() !== TaskStatuses::DONE ) {
                return false;
            }
        }

        return true;
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