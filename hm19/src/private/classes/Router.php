<?php

namespace Classes;

class Router extends AbstractHandler
{
    private string $basePath;
    private array $routes = [];

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    public function route(string $path): Route
    {
        $route = new Route($this->basePath . $path);
        $this->routes[] = $route;
        return $route;
    }

    public function handle(...$args): void
    {
        [$request, $response, $next] = $args;
        foreach ($this->routes as $route) {
            if ($route->matches($request->getUri()) && array_key_exists($request->getMethod(), $route->getHandlers())) {
                $route->handle($request, $response, $next);
            }
        }
        $next?->handle($request, $response, $next->getNext());
    }
}
