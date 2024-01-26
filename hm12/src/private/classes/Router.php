<?php

require_once "Response.php";

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, Closure $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => "/api" . $path,
            'handler' => $handler
        ];
    }

    private function handleRequest(string $method, string $uri): void
    {
        try {
            foreach ($this->routes as $route) {
                if ($route['method'] === $method && $this->matchPath($route['path'], $uri))
                    Response::success(200, call_user_func($route['handler']));
            }
            Response::error(404, "Route not found");
        } catch (Exception $ex) {
            if ($ex instanceof ApiException)
                Response::error($ex->getStatusCode(), $ex->getMessage());
            Response::error(500, $ex->getMessage());
        }
    }

    private function matchPath(string $pattern, string $uri): false|int
    {
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        return preg_match($pattern, $uri);
    }

    public function __destruct()
    {
        self::handleRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }
}