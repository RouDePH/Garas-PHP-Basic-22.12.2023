<?php namespace HM16_API;

use Closure;
use HM16_CLASSES\{ExceptionHandler, Application, Middleware, Request, Response, ConsoleLogger, IHandler};

use routers\BaseRouter;
use Throwable;

const API_DIR = __DIR__ . "/";

require_once API_DIR . "../classes/ExceptionHandler.php";
require_once API_DIR . "../classes/Application.php";
require_once API_DIR . "../classes/Middleware.php";

require_once API_DIR . "../classes/Router.php";
require_once API_DIR . "../classes/ConsoleLogger.php";

require_once API_DIR . "../routers/BaseRouter.php";

$app = new Application();

$middleware1 = new Middleware(function (Request $request, Response $response) {
    ConsoleLogger::log("Hello");
});

$middleware2 = new Middleware(function (Request $request, Response $response) {
    ConsoleLogger::log("world");
});

$baseRouter = BaseRouter::init();

$exceptionHandler = new ExceptionHandler();

$lastMiddle = new Middleware(function (Request $request, Response $response) {
    $response::error(404, "Route not found");
});

$app->use($middleware1);
$app->use($middleware2);
$app->use($baseRouter);
$app->use($exceptionHandler);
$app->use($lastMiddle);