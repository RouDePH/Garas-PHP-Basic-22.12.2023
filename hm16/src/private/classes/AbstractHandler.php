<?php

namespace HM16_CLASSES;

require_once 'IHandler.php';
require_once 'Request.php';
require_once 'Response.php';

abstract class AbstractHandler implements IHandler
{
    private ?IHandler $nextHandler = null;

    public function setNext(?IHandler $handler): IHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function getNext(): ?IHandler
    {
        return $this->nextHandler;
    }

    public abstract function handle(...$args): void;
}