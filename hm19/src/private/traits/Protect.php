<?php

namespace Traits;

use Database\UserRepository;
use Classes\{Request, Response};

use Closure;

trait Protect
{
    use JWTClient;
    use ExceptionHandling;

    static function protect(): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) {
                $accessToken = null;

                if ($req->hasHeader('Authorization') && str_starts_with($req->getHeader('Authorization'), 'Bearer')) {
                    $accessToken = explode(' ', $req->getHeader('Authorization'))[1];
                }

                if (!$accessToken) {
                    $res::error(401, "Provide access token");
                }

                $decoded = self::verifyAccessJWT($accessToken);

                $params = [
                    "id" => $decoded->id,
                    "active" => 1
                ];

                $user = UserRepository::select($params);

                if (!$user) {
                    $res::error(404, "The user with this token no longer exists");
                }

                $req->addAttribute('user', $user);
            }
        );
    }

    public static function restrict(...$roles): Closure
    {
        return self::handleException(
            function (Request $req, Response $res) use ($roles) {
                $userRole = $req->getAttribute('user')['role'] ?? null;
                if (!in_array($userRole, $roles)) {
                    $res::error(403, "You do not have permission to perform this action");
                }
            }
        );
    }
}