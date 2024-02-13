<?php

namespace Routers;

use Controllers\UserController;
use Classes\{Router};
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
                self::validateQueryParams('limit','offset'),
                self::protect(),
                // header Authorization: Bearer ACCESS_TOKEN
                // email: john@example.com - В нашем случае только для пользователя John,
                //        так как у него роль - admin
                self::restrict('admin'),
                UserController::getAllUsers()
            );

        $router->route('/signIn')
            ->post(
                self::validateBodyKeys('email', 'password'),
                // email: john@example.com, password: hashed_password
                UserController::signIn()
            );

        $router->route('/signUp')
            ->post(
                self::validateBodyKeys('full_name','email', 'password'),
                // email: john@example.com, password: hashed_password
                UserController::signUp()
            );

        $router->route('/getMe')
            ->get(
                self::protect(),
                // header Authorization: Bearer ACCESS_TOKEN
                UserController::getMe()
            );

        return $router;
    }
}