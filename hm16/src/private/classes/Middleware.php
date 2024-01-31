<?php

namespace HM16_CLASSES;

require_once 'AbstractHandler.php';
require_once 'IHandler.php';
require_once 'Request.php';
require_once 'Response.php';

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