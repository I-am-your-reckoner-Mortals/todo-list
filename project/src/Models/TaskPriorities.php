<?php


namespace App\Models;


class TaskPriorities
{
    public const VERY_LOW = 1;
    public const LOW = 2;
    public const MEDIUM = 3;
    public const HEIGHT = 4;
    public const VERY_HEIGHT = 5;

    public const PRIORITIES = [
        self::VERY_LOW,
        self::LOW,
        self::MEDIUM,
        self::HEIGHT,
        self::VERY_HEIGHT,
    ];
}