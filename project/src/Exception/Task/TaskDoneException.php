<?php


class TaskDoneException extends Exception
{
    protected $message = 'You cannot delete completed task';
}