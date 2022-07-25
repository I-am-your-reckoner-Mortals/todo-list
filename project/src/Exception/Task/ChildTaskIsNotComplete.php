<?php


class ChildTaskIsNotComplete extends Exception
{
    protected $message = 'You cannot change the status of this task until child tasks is not completed!';
}