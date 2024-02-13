<?php

namespace Traits;

use Classes\Request;
use Classes\Response;
use Closure;
use Interfaces\IHandler;
use Throwable;

trait ExceptionHandling
{
    static function handleException(callable $fn): Closure
    {
        return function (Request $req, Response $res, ?IHandler $next) use ($fn) {
            try {
                $fn($req, $res, $next);
            } catch (Throwable $exception) {
                $next->handle($req, $res, $next, $exception);
            }
        };
    }
}