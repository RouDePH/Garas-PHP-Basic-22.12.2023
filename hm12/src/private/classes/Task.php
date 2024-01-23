<?php

require_once "TaskStatus.php";

class Task
{
    private int $id;
    private string $name;
    private string $priority;
    private TaskStatus $status;

    public function __construct(string $name, string $priority, TaskStatus $status = TaskStatus::TASK_STATUS_NOT_COMPLETED)
    {
        $this->id = hexdec(uniqid());
        $this->name = $name;
        $this->priority = $priority;
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'priority' => $this->priority,
            'status' => $this->status,
        ];
    }

    public static function fromArray(array $array): Task
    {
        $task = new Task(
            $array['name'],
            $array['priority'],
            TaskStatus::fromString($array['status']));
        $task->setId($array['id']);
        return $task;
    }

    public function update(array $params): void
    {
        foreach ($params as $key => $value) {
            match ($key) {
                'name' => $this->setName($value),
                'status' => $this->setStatus(TaskStatus::fromString($value)),
                'priority' => $this->setPriority($value),
                default => null
            };
        }
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function getStringStatus(): string
    {
        return $this->status->value;
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }
}