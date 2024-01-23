<?php

require_once "Task.php";
require_once "TaskStatus.php";

class TaskManager
{
    private array $tasks = [];
    private string $filename;

    /**
     * @throws Exception
     */
    public function __construct(string $filePath)
    {
        $this->filename = $filePath;
        $this->loadTasks();
    }

    public function addTask(string $taskName, string $priority): Task
    {
        $task = new Task($taskName, $priority);
        $this->tasks[] = $task;
        $this->saveTasks();
        return $task;
    }

    public function deleteTask(int $taskId): ?bool
    {
        foreach ($this->tasks as $key => $task) {
            if ($task->getId() === $taskId) {
                unset($this->tasks[$key]);
                $this->saveTasks();
                return true;
            }
        }
        return null;
    }

    public function getTasks(): array
    {
        usort($this->tasks, function ($a, $b) {
            return $b->getPriority() - $a->getPriority();
        });

        return $this->tasks;
    }

    public function completeTask(int $taskId): void
    {
        foreach ($this->tasks as $task) {
            if ($task->getId() === $taskId) {
                $task->setStatus(TaskStatus::TASK_STATUS_COMPLETED);
                $this->saveTasks();
                break;
            }
        }
    }

    /**
     * @throws Exception
     */
    public function updateTask(int $taskId, array $params): ?Task
    {
        foreach ($this->tasks as $task) {
            if ($task->getId() === $taskId) {
                $task->update($params);
                $this->saveTasks();
                return $task;
            }
        }
        throw new Exception("Task with id $taskId does not exist!");
    }

    /**
     * @throws Exception
     */
    private function loadTasks(): void
    {
        if (file_exists($this->filename)) {
            $tasksData = json_decode(file_get_contents($this->filename), true);
            foreach ($tasksData as $taskData) {
                $this->tasks[] = Task::fromArray($taskData);
            }
        } else throw new Exception("Tasks file not exist!");
    }

    private function saveTasks(): void
    {
        $tasksData = [];
        foreach ($this->tasks as $task) {
            $tasksData[] = $task->toArray();
        }
        file_put_contents($this->filename, json_encode($tasksData, JSON_PRETTY_PRINT));
    }
}
