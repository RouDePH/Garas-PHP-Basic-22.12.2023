<?php

namespace Controllers;

use Closure;

use Classes\{Request, Response};
use Traits\{ExceptionHandling};

class RootController
{
    use ExceptionHandling;


    static function helloWold(): Closure
    {
        return self::handleException(function (Request $request, Response $response) {
            $response::success(200, ["hello" => "world"]);
        });
    }

    static function calculateSum(): Closure
    {
        return self::handleException(function (Request $request, Response $response) {
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
        });
    }
}