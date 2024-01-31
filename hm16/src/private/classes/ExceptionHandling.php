<?php

namespace HM16_CLASSES;

use Closure;
use Throwable;

require_once 'Request.php';
require_once 'Response.php';
require_once 'IHandler.php';

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