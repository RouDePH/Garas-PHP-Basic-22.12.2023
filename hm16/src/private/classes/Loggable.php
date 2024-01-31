<?php

namespace HM16_CLASSES;

require_once "ILogger.php";

trait Loggable
{
    protected ILogger $logger;

    public function setLogger(ILogger $logger): void
    {
        $this->logger = $logger;
    }

    private function getLogger(): ILogger
    {
        return $this->logger ?? ConsoleLogger::getInstance();
    }

    public function log(string $message): void
    {
        $this->getLogger()::log($message);
    }
}