<?php

namespace Traits;

use JWT\{JWT, Key};

use stdClass;

trait JWTClient
{
    static function signAccessJWT(array $payload): string
    {
        $current_time = time();
        $one_day = 86400;

        $payload = [
            'iat' => $current_time,
            'exp' => $current_time + $one_day,
            'payload' => $payload
        ];

        return JWT::encode($payload, getenv("JWT_KEY"), 'HS256');
    }

    static function verifyAccessJWT(string $jwt): stdClass
    {
        return JWT::decode($jwt, new Key(getenv("JWT_KEY"), 'HS256'))->payload;
    }
}