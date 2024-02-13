<?php

namespace Traits;

use Classes\ConsoleLogger;
use Interfaces\ILogger;

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