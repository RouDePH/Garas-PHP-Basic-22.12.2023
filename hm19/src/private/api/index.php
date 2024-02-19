<?php declare(strict_types=1);

namespace Api;

const API_DIR = __DIR__ . "/";
require_once realpath(API_DIR . '../autoload.php');

use Classes\{ExceptionHandler, Application, Middleware, Request, Response};
use Controllers\{CorsController, ErrorController};
use Routers\{RootRouter, UserRouter};

$app = new Application();

$app->use(new Middleware(
    CorsController::useCors()
));

$rootRouter = RootRouter::init();
$userRouter = UserRouter::init();

$app->use($rootRouter);
$app->use($userRouter);

$app->use(new ExceptionHandler(
    ErrorController::handleAPIException()
));

$app->use(new Middleware(
    ErrorController::handle404()
));
