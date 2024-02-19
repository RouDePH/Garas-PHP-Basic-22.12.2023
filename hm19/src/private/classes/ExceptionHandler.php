<?php

namespace Classes;

use Interfaces\IHandler;
use Traits\Loggable;

use Throwable;

class ExceptionHandler implements IHandler
{
    use Loggable;

    private ?IHandler $nextHandler = null;

    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function setNext(?IHandler $handler): IHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function getNext(): ?IHandler
    {
        return $this->nextHandler;
    }

    public function handle(...$args): void
    {
        [$request, $response, $next, $exception] = [...$args, null];

        if ($exception instanceof Throwable) {
            $callback = $this->callback;
            $callback($request, $response, $next, $exception);
        } else {
            $next?->handle($request, $response, $next->getNext(), ...array_slice($args, 3));
        }
    }
}
