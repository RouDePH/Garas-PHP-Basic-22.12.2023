<?php

namespace HM16_CLASSES;

require_once "Singleton.php";
require_once "ILogger.php";

class ConsoleLogger extends Singleton implements ILogger
{
    private $fileHandle;

    protected function __construct()
    {
        $this->fileHandle = fopen('php://stdout', 'w');
    }

    public function writeLog(string $message): void
    {
        $date = date('Y-m-d');
        fwrite($this->fileHandle, "$date: $message\n");
    }

    public static function log(string $message): void
    {
        $logger = static::getInstance();
        $logger->writeLog($message);
    }
}
