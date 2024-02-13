<?php

namespace Classes;

class Middleware extends AbstractHandler
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function handle(...$args): void
    {
        [$request, $response, $next] = $args;
        $callback = $this->callback;
        $callback($request, $response, $next);
        $next?->handle($request, $response, $next->getNext());
    }
}