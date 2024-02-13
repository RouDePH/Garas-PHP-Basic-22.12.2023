<?php

namespace Controllers;

use Traits\{ExceptionHandling, JWTClient};
use Classes\{Request, Response};
use Models\UserRepository;

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
                    "password" => $body["password"]
                ];

                $user = UserRepository::getByParams($params);

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

                UserRepository::create($params);

                $searchParams = [
                    "email" => $body["email"]
                ];

                $user = UserRepository::getByParams($searchParams);
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

                $users = UserRepository::getAll($queryParams['offset'], $queryParams['limit']);
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
                $userId = $body['user_id'];

                $result = UserRepository::delete($userId);

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

                $result = UserRepository::update($userId, [
                    "active" => 0,
                    "deleted_at" => date("Y-m-d H:i:s", time())
                ]);

                if (!$result) {
                    $res::error(404, "The user with this id not found");
                }

                $res::success(200);
            }
        );
    }
}
