<?php

class ApiException extends Exception
{
    private int $statusCode;
    private bool $logging;

    public function __construct(string $message = "", int $statusCode = 500, bool $logging = false, int $code = 0, ?Throwable $previous = null)
    {
        $this->statusCode = $statusCode;
        $this->logging = $logging;
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function isLogging(): bool
    {
        return $this->logging;
    }
}
