<?php

namespace HM16_CLASSES;

require_once 'Request.php';
require_once 'Response.php';

interface IHandler
{
    public function setNext(?IHandler $handler): IHandler;

    public function getNext(): ?IHandler;

    public function handle(...$args): void;
}