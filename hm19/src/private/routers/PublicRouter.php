<?php

namespace Routers;

use Classes\{Middleware, Request, Response};
use Traits\ExceptionHandling;

class PublicRouter
{
    use ExceptionHandling;

    static function init(): Middleware
    {
        return new Middleware(
            self::handleException(
                function (Request $request, Response $response) {
                    // контроллер и действие по умолчанию
                    $controller_name = 'Main';
                    $action_name = 'index';

                    $routes = explode('/', $_SERVER['REQUEST_URI']);

                    if (!empty($routes[1])) {
                        $controller_name = ucfirst($routes[1]);
                    }

                    if (!empty($routes[2])) {
                        $action_name =  ucfirst($routes[2]);
                    }

                    $model_name = 'Model' . $controller_name;
                    $controller_name = 'Controller' . $controller_name;

                    $model_file = strtolower($model_name) . '.php';
                    $model_path = __DIR__ . "/../models/" . $model_file;
                    if (file_exists($model_path)) {
                        include $model_path;
                    }

                    $controller_file = $controller_name . '.php';
                    $controller_path = __DIR__ . "/../controllers/" . $controller_file;
                    if (file_exists($controller_path)) {
                        include $controller_path;
                    } else {
                        $controller_name = "Controller404";
                        $action_name = "Index";
                    }

                    $controller_class = 'Controllers\\' . $controller_name;
                    $controller = new $controller_class;

                    $action = $action_name;

                    if (method_exists($controller, $action)) {
                        $controller->$action();
                    }else{
                        $controller_name = "Controller404";
                        $action_name = "index";

                        $controller_class = 'Controllers\\' . $controller_name;
                        $controller = new $controller_class;

                        $action = $action_name;


                        $controller->$action();
                    }
                }
            )
        );
    }
}