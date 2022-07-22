<?php


namespace App\Models;


class TaskStatuses
{
    public const NONE = 'none';
    public const ANALYZE = 'anlyze';
    public const TODO = 'todo';
    public const IN_PROGRESS = 'in_progress';
    public const DONE = 'done';
    public const REJECT = 'reject';

    public const STATUSES = [
        self::NONE,
        self::ANALYZE,
        self::TODO,
        self::IN_PROGRESS,
        self::DONE,
        self::REJECT,
    ];
}