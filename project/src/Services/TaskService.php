<?php

namespace App\Services;

use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Entity\User;
use App\Models\TaskStatuses;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Validator\TaskValidator;
use ChildTaskIsNotComplete;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use TaskDoneException;

class TaskService
{
    private WorkflowInterface $taskStateMachine;
    private EntityManagerInterface $entityManager;
    private TaskValidator $taskValidator;
    private TaskRepository $taskRepository;
    private UserRepository $userRepository;

    public function __construct(
        WorkflowInterface $taskStateMachine,
        EntityManagerInterface $entityManager,
        TaskValidator $taskValidator,
        TaskRepository $taskRepository,
        UserRepository $userRepository
    ) {
        $this->taskStateMachine = $taskStateMachine;
        $this->entityManager = $entityManager;
        $this->taskValidator = $taskValidator;
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
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

    /**
     * @throws \Exception
     */
    public function edit(
        Task $task,
        TaskDTO $data
    ): Task {
        $task->setUpdatedAt();

        $task->setTitle($data->title ?? $task->getTitle());
        $task->setPriority($data->priority ?? $task->getPriority());
        $task->setStatus($data->status ?? $task->getStatus());
        $task->setDescription($data->description ?? $task->getDescription());

        if (isset($data->createdBy->id) && !is_null($data->createdBy->id)) {
            $user = $this->userRepository->findOneBy(['id' => $data->createdBy->id]);
            if (is_null($user)) {
                throw new \Exception('createdBy user with this id not found');
            }
            $task->setCreatedBy($user);
        }

        if (isset($data->assignTo->id) && !is_null($data->assignTo->id)) {
            $user = $this->userRepository->findOneBy(['id' => $data->assignTo->id]);
            if (is_null($user)) {
                throw new \Exception('assignTo user with this id not found');
            }
            $task->setAssignTo($user);
        }

        $task->setCreatedAt(new DateTime($data->createdAt) ?? $task->getCreatedAt());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
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

    /**
     * @throws ChildTaskIsNotComplete
     */
    public function update(Task $task): void
    {
        if($task->getStatus() === TaskStatuses::DONE) {
            foreach ($this->taskRepository->findChildTasks($task) as $item) {
                if ($item->getStatus() !== TaskStatuses::DONE) {
                    throw new ChildTaskIsNotComplete();
                }
            }
            $task->setCompletedAt(new DateTime());
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function getAllowedStatuses(Task $task): array
    {
        $current = array_keys($this->taskStateMachine->getMarking($task)->getPlaces())[0];
        $statuses[$current] = $current;

        $transitions = $this->taskStateMachine->getEnabledTransitions($task);

        foreach ($transitions as $transition ) {
            foreach ($transition->getTos() as $toState) {
                $statuses[$toState] = $toState;
            }
        }

        return $statuses;
    }
}