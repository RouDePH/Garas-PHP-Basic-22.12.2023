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
            self::log('[ERROR][' . $exception->getFile() . '][' . $exception->getLine() . '] - ' . $exception->getMessage());
            if ($exception instanceof ApiException) {
                $response::error($exception->getStatusCode(), $exception->getMessage());
            } else {
                $response::error(500, $exception->getMessage());
            }
        } else {
            $next?->handle($request, $response, $next->getNext());
        }
    }
}
