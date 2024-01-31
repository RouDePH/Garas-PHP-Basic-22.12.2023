<?php

namespace HM16_CLASSES;

use JetBrains\PhpStorm\NoReturn;

class Response
{
    protected static int $status = 200;

    public static function status(int $status): self
    {
        static::$status = $status;
        return new static();
    }

    #[NoReturn] public static function json(mixed $data): void
    {
        static::sendResponse($data, static::$status);
    }

    #[NoReturn] public static function error(int $code, string $message): void
    {
        static::sendResponse(['status' => "error", 'message' => $message], $code);
    }

    #[NoReturn] public static function success(int $code, mixed $data = null): void
    {
        static::sendResponse(['status' => "success", 'data' => $data], $code);
    }

    #[NoReturn] private static function sendResponse(mixed $data, ?int $code = null): void
    {
        http_response_code($code ?? static::$status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}