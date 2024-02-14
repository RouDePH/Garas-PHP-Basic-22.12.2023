<?php

namespace Routers;

use Traits\{ExceptionHandling, InputValidators};
use Controllers\RootController;
use Classes\Router;

class RootRouter
{
    use ExceptionHandling;
    use InputValidators;

    public static function init(): Router
    {
        $router = new Router("/api/");
        $router->route("")
            ->get(
                RootController::helloWorld()
            )
            ->post(
                self::validateBodyKeys("firstNumber", "secondNumber"),
                RootController::calculateSum()
            );

        return $router;
    }
}
