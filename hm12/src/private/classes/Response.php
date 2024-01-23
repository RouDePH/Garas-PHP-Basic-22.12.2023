<?php

use JetBrains\PhpStorm\NoReturn;

class Response
{
    #[NoReturn] public static function error(int $code, string $message): void
    {
        self::sendResponse($code, ['status' => "error", 'message' => $message]);
    }

    #[NoReturn] public static function success(int $code, mixed $data = null): void
    {
        self::sendResponse($code, ['status' => "success", 'data' => $data]);
    }

    #[NoReturn] private static function sendResponse(int $code, mixed $data): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}