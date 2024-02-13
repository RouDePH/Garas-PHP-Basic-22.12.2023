<?php

namespace Controllers;

use Traits\{ExceptionHandling, JWTClient};
use Classes\{Request, Response};
use Database\UserRepository;

use Closure;

class UserController
{
    use ExceptionHandling;
    use JWTClient;

    static function signIn(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $body = $req->getBody();

                $params = [
                    "email" => $body["email"],
                    "password" => $body["password"],
                    "active" => 1
                ];

                $user = UserRepository::select($params);

                if (!$user) {
                    $res::error(404, "User not found");
                }

                $token = self::signAccessJWT(["id" => $user["id"]]);

                $res::success(200, ["accessToken" => $token, "user" => $user]);
            }
        );
    }

    static function signUp(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $body = $req->getBody();

                $params = [
                    "full_name" => $body["full_name"],
                    "email" => $body["email"],
                    "password" => $body["password"]
                ];

                UserRepository::insert($params);

                $searchParams = [
                    "email" => $body["email"]
                ];

                $user = UserRepository::select($searchParams);
                $token = self::signAccessJWT(["id" => $user["id"]]);

                $res::success(200, ["accessToken" => $token, "user" => $user]);
            }
        );
    }

    static function getAllUsers(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $queryParams = $req->getQueryParams();

                $users = UserRepository::select(
                    ["active" => 1],
                    ["id", "full_name", "email", "active", "role"],
                    $queryParams['offset'],
                    $queryParams['limit']
                );

                $count = UserRepository::count();

                $res::success(200, [...$count, "users" => $users]);
            }
        );
    }

    static function getMe(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $user = $req->getAttributes()['user'];
                $res::success(200, ["user" => $user]);
            }
        );
    }

    static function delete(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $body = $req->getBody();
                $params = [
                    "id" => $body['user_id']
                ];

                $result = UserRepository::delete($params);

                if (!$result) {
                    $res::error(404, "The user with this id not found");
                }

                $res::success(200);
            }
        );
    }

    static function deactivate(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $userId = $req->getAttribute("user")['id'];

                $params = [
                    "id" => $userId
                ];

                $result = UserRepository::update(
                    $params,
                    [
                        "active" => 0,
                        "deleted_at" => date("Y-m-d H:i:s", time())
                    ]
                );

                if (!$result) {
                    $res::error(404, "The user with this id not found");
                }

                $res::success(200);
            }
        );
    }
}
