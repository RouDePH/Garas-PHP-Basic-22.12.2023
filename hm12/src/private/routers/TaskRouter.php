<?php

require_once API_PATH . '../classes/Router.php';
require_once API_PATH . '../classes/Response.php';
require_once API_PATH . '../classes/TaskManager.php';
require_once API_PATH . '../classes/ApiException.php';
require_once API_PATH . '../classes/InputValidator.php';

class TaskRouter
{
    public static function registerRoutes(string $path, Router $router): void
    {
        $router->addRoute('GET', $path . '/', function () {
            $taskManager = new TaskManager(PRIVATE_ROOT_PATH . "tasks.json");
            return array_map(fn($task): array => $task->toArray(), $taskManager->getTasks());
        });

        $router->addRoute('POST', $path . '/', function () {
            $body = json_decode(file_get_contents("php://input"), true) ?? null;
            if (is_array($body)) {
                InputValidator::validateKeys($body, ['name', 'priority']);
                $taskManager = new TaskManager(PRIVATE_ROOT_PATH . "tasks.json");
                $newTask = $taskManager->addTask($body['name'], $body['priority']);
                return $newTask->toArray();
            } else throw new ApiException("Provide task model!", "400");
        });

        $router->addRoute('PATCH', $path . '/', function () {
            $body = json_decode(file_get_contents("php://input"), true) ?? null;
            if (is_array($body)) {
                InputValidator::validateKeys($body, ['id']);
                $taskManager = new TaskManager(PRIVATE_ROOT_PATH . "tasks.json");
                $result = $taskManager->updateTask($body['id'], $body);
                if ($result) return $result->toArray();
                else throw new ApiException("Task with id does not exist!", "400");
            } else throw new ApiException("Provide task model!", "400");
        });

        $router->addRoute('DELETE', $path . '/', function () {
            $body = json_decode(file_get_contents("php://input"), true) ?? null;
            if (is_array($body)) {
                InputValidator::validateKeys($body, ['id']);
                $taskManager = new TaskManager(PRIVATE_ROOT_PATH . "tasks.json");
                $result = $taskManager->deleteTask($body['id']);
                if ($result) return ["message" => "Task deleted"];
                else throw new ApiException("Task with id does not exist!", "400");
            } else throw new ApiException("Provide task model!", "400");
        });
    }
}