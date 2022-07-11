<?php


use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class TaskDTO
{
    /**
     * @Assert\NotBlank
    */
    public string $title;

    /**
     * @Assert\NotBlank
     */
    public string $status;

    /**
     * @Assert\NotBlank
     */
    public int $priority;

    public ?string $description;

    public User $createdBy;
}