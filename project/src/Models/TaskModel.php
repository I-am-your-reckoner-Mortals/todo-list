<?php


namespace App\Models;


class TaskModel
{
    public string $title;
    public string $priority;
    public string $status;
    public ?string $description;
    public int $assignTo;
    public int $createdBy;
    public string $createdAt;
    public ?string $updatedAt;
}