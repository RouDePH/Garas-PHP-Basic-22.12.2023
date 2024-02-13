<?php

namespace Routers;

use Classes\{Request, Response, Router};
use Traits\{ExceptionHandling};

class BaseRouter
{
    use ExceptionHandling;

    const PARTNER_ID = "4";
    const PARTNER_KEY = "12345";

    public static function init(): Router
    {
        $router = new Router("/api/");
        $router->route('')
            ->get(
                self::handleException(function (Request $request, Response $response) {
                    $response::success(200, ["hello" => "world"]);
                })
            )
            ->post(
                self::handleException(function (Request $request, Response $response) {
                    $response::success(200, ["hello" => "world"]);
                })
            );
        return $router;
    }
}
