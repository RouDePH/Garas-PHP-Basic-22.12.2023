<?php

namespace Routers;

use Classes\{Request, Response, Router};
use Traits\{ExceptionHandling, InputValidators};

class RootRouter
{
    use ExceptionHandling;
    use InputValidators;

    public static function init(): Router
    {
        $router = new Router("/api/");
        $router->route("")
            ->get(
                self::handleException(function (Request $request, Response $response) {
                    $response::success(200, ["hello" => "world"]);
                })
            )
            ->post(
                self::validateBodyKeys("firstNumber", "secondNumber"),
                self::handleException(function (Request $request, Response $response) {
                    $body = $request->getBody();

                    $firstNumber = $body["firstNumber"];
                    $secondNumber = $body["secondNumber"];

                    $response::success(200,
                        [
                            "firstNumber" => $firstNumber,
                            "secondNumber" => $secondNumber,
                            "sum" => $firstNumber + $secondNumber
                        ]
                    );
                })
            );
        return $router;
    }
}
