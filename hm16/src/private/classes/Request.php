<?php

namespace HM16_CLASSES;

class Request
{
    private array $body;
    private string $method;
    private array $queryParams;
    private string $uri;

    public function __construct()
    {
        $this->setBody();
        $this->setMethod();
        $this->setUri();
        $this->setQueryParams();
    }

    public function setBody(?array $body = null): void
    {
        $this->body = $body ?? json_decode(file_get_contents("php://input"), true) ?? [];
    }

    public function setMethod(?string $method = null): void
    {
        $this->method = $method ?? $_SERVER['REQUEST_METHOD'];
    }

    public function setUri(?string $uri = null): void
    {
        $this->uri = $uri ?? strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function setQueryParams(?array $queryParams = null): void
    {
        $this->queryParams = $queryParams ?? $_GET ?? [];
    }
}