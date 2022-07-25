<?php

namespace App\DTO;

use DateTime;

class TaskDTO implements DTOInterface
{
    public int $id;
    public string $title;
    public string $status;
    public int $priority;
    public ?string $description;

    public UserDTO $createdBy;
    public ?UserDTO $assignTo;
    public ?DateTime $createdAt;
    public ?DateTime $updatedAt;
    public ?DateTime $completedAt;
}