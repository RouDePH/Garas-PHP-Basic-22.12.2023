<?php

const PUBLIC_DIR = __DIR__ . "/";
require_once realpath(PUBLIC_DIR . '../private/autoload.php');

use Classes\{Application, ExceptionHandler, Request, Response};
use Interfaces\IHandler;
use Routers\PublicRouter;

$application = new Application();

$application->use(PublicRouter::init());

$application->use(new ExceptionHandler(
    function (Request $request, Response $response, ?IHandler $next, Throwable $exception) {
        echo "<pre>";
        var_dump($exception);
        echo "<pre>";
        exit();
    }
));