<?php

namespace Controllers;

use Interfaces\IHandler;
use Classes\{ApiException, Request, Response};
use JWT\ExpiredException;
use Traits\ExceptionHandling;

use Throwable;
use Closure;

class ErrorController
{
    use ExceptionHandling;

    static function handleAPIException(): Closure
    {
        return function (Request $request, Response $response, ?IHandler $next, Throwable $exception) {
            switch (true) {
                case $exception instanceof ApiException:
                    response::error($exception->getStatusCode(), $exception->getMessage());
                case $exception instanceof ExpiredException:
                    response::error(498, $exception->getMessage());
                default:
                    response::error(500, $exception->getMessage());
            }
        };
    }

    static function handle404(): Closure
    {
        return function (Request $request, Response $response) {
            $response::error(404, "Route [" . $request->getMethod() . "][" . $request->getUri() . "] not found");
        };
    }
}