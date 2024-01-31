<?php

namespace routers;

use HM16_CLASSES\{ConsoleLogger, ExceptionHandling, Request, Response, Router};

require_once  "../classes/ExceptionHandling.php";

use Exception;

class BaseRouter
{
    use ExceptionHandling;

    public static function init(): Router
    {
        $baseRouter = new Router("/api/");
        $baseRouter->route('')
            ->get(
                self::handleException(function (Request $request, Response $response) {
                    ConsoleLogger::log("Base API route");
                }),
                self::handleException(function (Request $request, Response $response) {
                    $response::success(200, $request->getBody());
                })
            );

        $baseRouter->route('test')
            ->get(
                self::handleException(function (Request $request, Response $response) {
                    throw new Exception("Joke");
                }),
                self::handleException(function (Request $request, Response $response) {
                    $response::status(200)::json($request->getBody());
                })
            )
            ->post(
                self::handleException(function (Request $request, Response $response) {
                    $response::error(401, "Unauthorized");
                })
            );

        return $baseRouter;
    }
}