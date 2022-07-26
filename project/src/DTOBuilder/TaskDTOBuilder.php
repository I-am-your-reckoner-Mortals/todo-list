<?php


namespace App\DTOBuilder;


use App\Entity\Task;
use App\DTO\TaskDTO;

class TaskDTOBuilder
{
    public function build(Task $task): TaskDTO
    {
        $dto = new TaskDTO();

        $dto->id = $task->getId();
        $dto->title = $task->getTitle();
        $dto->status = $task->getStatus();
        $dto->priority = $task->getPriority();
        $dto->description = $task->getDescription();
        $dto->createdBy = (new UserDTOBuilder())->build($task->getCreatedBy());
        $dto->assignTo = $task->getAssignTo()
            ? (new UserDTOBuilder())->build($task->getAssignTo())
            : null;
        $dto->createdAt = $task->getCreatedAt();
        $dto->updatedAt = $task->getUpdatedAt();
        $dto->updatedAt = $task->getCompletedAt();

        return $dto;
    }
}