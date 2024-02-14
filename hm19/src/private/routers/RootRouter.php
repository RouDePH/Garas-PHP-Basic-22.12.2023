<?php

namespace Routers;

use Controllers\RootController;
use Classes\{Router};
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
                RootController::helloWold()
            )
            ->post(
                self::validateBodyKeys("firstNumber", "secondNumber"),
                RootController::calculateSum()
            );
        return $router;
    }
}
