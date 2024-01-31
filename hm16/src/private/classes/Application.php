<?php

namespace HM16_CLASSES;

require_once 'AbstractHandler.php';
require_once 'Request.php';
require_once 'Response.php';

class Application
{
    private IHandler $middleware;
    private Request $request;
    private Response $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->middleware = new Middleware(function (Request $request, Response $response) {});
    }

    public function use(IHandler $middleware): void
    {
        $lastMiddleware = $this->getLastMiddleware();
        $lastMiddleware->setNext($middleware);
    }

    private function getLastMiddleware(): IHandler
    {
        $currentMiddleware = $this->middleware;
        while ($currentMiddleware->getNext()) {
            $currentMiddleware = $currentMiddleware->getNext();
        }
        return $currentMiddleware;
    }

    private function handle(): void
    {
        $this->middleware->handle($this->request, $this->response, $this->middleware->getNext());
    }

    public function __destruct()
    {
        $this->handle();
    }
}