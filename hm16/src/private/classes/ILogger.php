<?php

namespace HM16_CLASSES;

interface ILogger
{
    public function writeLog(string $message): void;

    public static function log(string $message): void;
}