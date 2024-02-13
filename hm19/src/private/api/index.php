<?php declare(strict_types=1);

namespace Api;

const API_DIR = __DIR__ . "/";
require_once realpath(API_DIR . '../autoloader/autoload.php');

use Classes\{ExceptionHandler, Application, Middleware, Request, Response};
use Routers\{RootRouter, UserRouter};

$app = new Application();

$rootRouter = RootRouter::init();
$userRouter = UserRouter::init();
$exceptionHandler = new ExceptionHandler();

$app->use($rootRouter);
$app->use($userRouter);
$app->use($exceptionHandler);

$app->use(new Middleware(function (Request $request, Response $response) {
    $response::error(404, "Route [" . $request->getUri() . "] not found");
}));
