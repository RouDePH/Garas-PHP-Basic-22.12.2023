<?php

namespace Interfaces;

interface IHandler
{
    public function setNext(?IHandler $handler): IHandler;

    public function getNext(): ?IHandler;

    public function handle(...$args): void;
}