<?php

namespace Controllers;

use Classes\{MysqlQueryBuilder, Request, Response};
use Models\UserRepository;
use Traits\ExceptionHandling;

use Closure;
use Traits\JWTClient;

class UserController
{
    use ExceptionHandling;
    use JWTClient;


    //TODO: REFACTOR
    static function signIn(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $body = $req->getBody();

                $queryBuilder = new MysqlQueryBuilder();

                $query = $queryBuilder
                    ->select("users", ['id', "full_name", "email", "active", "role"])
                    ->where("email")
                    ->where("password")
                    ->getSQL();

                $userRepository = new UserRepository();
                $user = $userRepository->query($query, [$body['email'], $body['password']])[0] ?? null;

                if (!$user) {
                    $res::error(404, "User not found");
                }

                $token = self::signAccessJWT(["id" => $user["id"]]);

                $res::success(200, ["accessToken" => $token, "user" => $user]);
            }
        );
    }

    //TODO: REFACTOR
    static function signUp(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {

                $body = $req->getBody();
                $queryBuilder = new MysqlQueryBuilder();

                $query = $queryBuilder
                    ->insert("users", ["full_name", "email", "password"])
                    ->getSQL();

                $userRepository = new UserRepository();
                $userRepository->query($query, [$body['full_name'], $body['email'], $body['password']]);

                $query = $queryBuilder
                    ->select("users", ['id', "full_name", "email", "active", "role"])
                    ->where("email")
                    ->getSQL();

                $user = $userRepository->query($query, [$body['email']])[0];

                $token = self::signAccessJWT(["id" => $user["id"]]);
                $user["token"] = $token;

                $res::success(200, $user);
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
                $res::success(200, $req->getAttributes()['user']);
            }
        );
    }

    static function delete(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $userRepository = new UserRepository();

                $body = $req->getBody();
                $userId = $body['user_id'];

                $result = $userRepository::delete($userId);

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
                $userRepository = new UserRepository();

                $userId = $req->getAttribute("user")['id'];

                $result = $userRepository::update($userId, [
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
