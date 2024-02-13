<?php

namespace Interfaces;

interface ILogger
{
    public function writeLog(string $message): void;

    public static function log(string $message): void;
}