<?php

namespace App\Entity;

use App\Models\TaskPriorities;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task extends BaseEntity
{
    const PRIORITIES = [
        TaskPriorities::VERY_LOW => 'very low',
        TaskPriorities::LOW => 'low',
        TaskPriorities::MEDIUM => 'medium',
        TaskPriorities::HEIGHT => 'height',
        TaskPriorities::VERY_HEIGHT => 'very height',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $status;

    /**
     * @ORM\Column(type="integer")
     */
    private int $priority;

    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
    */
    private User $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     */
    private ?User $assignTo = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
    */
    private ?DateTime $completedAt = null;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="task")
    */
    private $parentTask;

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getPriorityName(): string
    {
        return self::PRIORITIES[$this->priority];
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getAssignTo(): ?User
    {
        return $this->assignTo;
    }

    public function setAssignTo(?User $assignTo): void
    {
        $this->assignTo = $assignTo;
    }

    public function getParentTask(): ?Task
    {
        return $this->parentTask;
    }

    public function setParentTask(?Task $task): void
    {
        $this->parentTask = $task;
    }

    public function getCompletedAt(): ?DateTime
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?DateTime $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    public function __toString(): string
    {
        return $this->id . ': ' . $this->title;
    }
}
