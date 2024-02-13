<?php

namespace Classes;

use Traits\Loggable;

class Route extends AbstractHandler
{
    use Loggable;

    private string $path;
    private array $handlers = [];

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function get(callable ...$handlers): self
    {
        $this->handlers['GET'] = $handlers;
        return $this;
    }

    public function post(callable ...$handlers): self
    {
        $this->handlers['POST'] = $handlers;
        return $this;
    }

    public function put(callable ...$handlers): self
    {
        $this->handlers['PUT'] = $handlers;
        return $this;
    }

    public function patch(callable ...$handlers): self
    {
        $this->handlers['PATCH'] = $handlers;
        return $this;
    }

    public function delete(callable ...$handlers): self
    {
        $this->handlers['DELETE'] = $handlers;
        return $this;
    }

    public function matches(string $uri): bool
    {
        return $this->path === $uri;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function handle(...$args): void
    {
        [$request, $response, $next] = $args;
        $method = $request->getMethod();
        if (isset($this->handlers[$method])) {
            foreach ($this->handlers[$method] as $handler) {
                self::log(
                    '[' . $request->getMethod() . ']'
                        . '[' . $request->getUri() . ']'
                        . '[' . http_build_query($request->getQueryParams(), '', ", ") . ']'
                        . '[' . http_build_query($request->getBody(), '', ", ") . ']'
                );
                $handler($request, $response, $next);
            }
        }
        $next?->handle($request, $response, $next->getNext());
    }
}
