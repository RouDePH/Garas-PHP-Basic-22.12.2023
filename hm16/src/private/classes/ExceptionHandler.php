<?php

namespace HM16_CLASSES;

use Exception;

require_once 'Request.php';
require_once 'Response.php';
require_once 'IHandler.php';
require_once 'Loggable.php';

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
        if ($exception instanceof Exception) {
            self::log('[ERROR][' . $exception->getFile() . '][' . $exception->getLine() . '] - ' . $exception->getMessage());
            $response::error(500, $exception->getMessage());
        } else {
            $next?->handle($request, $response, $next->getNext());
        }
    }
}
