<?php

namespace Classes;

use Interfaces\IHandler;

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