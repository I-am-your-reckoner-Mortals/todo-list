<?php

namespace App\DTO;

use App\Models\TaskPriorities;
use App\Models\TaskStatuses;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

class TaskDTO implements DTOInterface
{
    public int $id;

    /**
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage = "Task title must be longer than {{ min }} characters long",
     *     maxMessage = "Task title must be shorter than {{ max }} characters long"
     * )
    */
    public string $title;

    /**
     * @Assert\Choice(choices=TaskStatuses::STATUSES, message="Allowed statuses is {{ choices }}")
    */
    public string $status;

    /**
     * @Assert\Range(min=1, max=5, notInRangeMessage="Priority must be between {{ min }} and {{ max }}")
     */
    public int $priority;

    /**
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage = "Task title must be longer than {{ min }} characters long",
     *     maxMessage = "Task title must be shorter than {{ max }} characters long"
     * )
     */
    public ?string $description;

    /**
     * @Assert\Type("App\DTO\UserDTO")
    */
    public UserDTO $createdBy;

    /**
     * @Assert\Type("App\DTO\UserDTO")
     */
    public ?UserDTO $assignTo = null;

    /**
     * @Assert\DateTime
    */
    public ?DateTime $createdAt= null;

    /**
     * @Assert\DateTime
     */
    public ?DateTime $updatedAt= null;

    /**
     * @Assert\DateTime
     */
    public ?DateTime $completedAt = null;
}