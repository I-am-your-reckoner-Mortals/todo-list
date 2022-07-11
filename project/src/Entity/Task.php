<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task extends BaseEntity
{
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
    private ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
    */
    private User $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     */
    private ?User $assignTo;

    /**
     * @var Task[]
     *
     * @ORM\ManyToMany(targetEntity="Task")
    */
    private $childTask;

    public function __construct()
    {
        $this->childTask = new ArrayCollection();
    }

    public function getId(): ?int
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

    /**
     * @return Task[]
     */
    public function getChildTask(): array
    {
        return $this->childTask;
    }

    public function addChildTask(Task $task): self
    {
        if (!$this->childTask->contains($task)) {
            $this->childTask[] = $task;
        }
        return $this;
    }
    public function removeChildTask(Task $task): self
    {
        $this->childTask->removeElement($task);
        return $this;
    }
}
