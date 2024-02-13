<?php

namespace Classes;

class Request
{
    private array $body;
    private array $attributes = [];
    private string $method;
    private array $queryParams;
    private string $uri;
    private array $headers;

    public function __construct()
    {
        $this->setBody();
        $this->setMethod();
        $this->setUri();
        $this->setQueryParams();
        $this->setHeaders();
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

    private function setHeaders(): void
    {
        foreach ($_SERVER as $key => $value) {
            if (!str_starts_with($key, 'HTTP_')) {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $this->headers[$header] = $value;
        }
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
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

    function hasHeader(string $headerName): bool
    {
        foreach ($this->headers as $header => $value) {
            if (strcasecmp($header, $headerName) === 0) {
                return true;
            }
        }
        return false;
    }

    function getHeader(string $headerName): ?string
    {
        foreach ($this->headers as $header => $value) {
            if (strcasecmp($header, $headerName) === 0) {
                return $value;
            }
        }
        return null;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $key): ?array
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        } else {
            return null;
        }
    }

    public function addAttribute(string $key, mixed $attribute): void
    {
        $this->attributes[$key] = $attribute;
    }
}