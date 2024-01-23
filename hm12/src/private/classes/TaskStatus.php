<?php

enum TaskStatus: string
{
    case TASK_STATUS_NOT_COMPLETED = 'not completed';
    case TASK_STATUS_COMPLETED = 'completed';

    /**
     * @throws Exception
     */
    public static function fromString(string $status): TaskStatus
    {
        return match ($status) {
            self::TASK_STATUS_COMPLETED->value => self::TASK_STATUS_COMPLETED,
            self::TASK_STATUS_NOT_COMPLETED->value => self::TASK_STATUS_NOT_COMPLETED,
            default => throw new Exception("Task status with provided value does not exist")
        };
    }
}