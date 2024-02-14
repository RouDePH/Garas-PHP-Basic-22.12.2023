<?php

namespace Classes;

use Interfaces\IHandler;
use Throwable;
use Traits\Loggable;

class ExceptionHandler implements IHandler
{
    use Loggable;

    private ?IHandler $nextHandler = null;

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
            $statusCode = $exception instanceof ApiException ? $exception->getStatusCode() : 500;
            $response::error($statusCode, $exception->getMessage());
        } else {
            $next?->handle($request, $response, $next->getNext());
        }
    }
}
