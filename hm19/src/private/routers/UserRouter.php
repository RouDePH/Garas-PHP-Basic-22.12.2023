<?php

namespace Routers;

use Controllers\UserController;
use Classes\Router;
use Traits\{InputValidators, Protect};

class UserRouter
{
    use Protect;
    use InputValidators;

    public static function init(): Router
    {
        $router = new Router("/api/user");
        $router->route('')
            ->get(
                self::validateQueryParams('limit', 'offset'),
                self::protect(),
                self::restrict('admin'),
                UserController::getAllUsers()
            )
            ->delete(
                self::validateBodyKeys('user_id'),
                self::protect(),
                self::restrict('admin'),
                UserController::delete()
            );

        $router->route('/signIn')
            ->post(
                self::validateBodyKeys('email', 'password'),
                UserController::signIn()
            );

        $router->route('/signUp')
            ->post(
                self::validateBodyKeys('full_name', 'email', 'password'),
                UserController::signUp()
            );

        $router->route('/getMe')
            ->get(
                self::protect(),
                UserController::getMe()
            );

        $router->route('/deactivate')
            ->delete(
                self::protect(),
                UserController::deactivate()
            );

        return $router;
    }
}