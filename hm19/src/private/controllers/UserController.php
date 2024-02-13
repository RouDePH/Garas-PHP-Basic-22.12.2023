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

    static function getAllUsers(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $queryBuilder = new MysqlQueryBuilder();

                $queryParams = $req->getQueryParams();

                $query = $queryBuilder
                    ->select("users", ['id', "full_name", "email", "active", "role"])
                    ->limit($queryParams['offset'], $queryParams['limit'])
                    ->getSQL();

                $userRepository = new UserRepository();
                $users = $userRepository->query($query);

                $query = $queryBuilder
                    ->select("users", ['COUNT(*) as count'])
                    ->getSQL();

                $count = $userRepository->query($query)[0]["count"];

                $res::success(200, ["count" => $count, "users" => $users]);
            }
        );
    }

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
                $user["token"] = $token;

                $res::success(200, $user);
            }
        );
    }

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


    static function getMe(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $res::success(200, $req->getAttributes()['user']);
            }
        );
    }
}
