<?php

const LOGFILE = ABSOLUTE_PATH . "logs/logfile.txt";

function showLogs(): void
{
    $fileContents = file_get_contents(LOGFILE);

    if (empty($fileContents)) {
        echo "File " . LOGFILE . " is empty or not exist." . PHP_EOL;
        return;
    }

    echo "LOGFILE:" . PHP_EOL . $fileContents . PHP_EOL;
}

function writeLog($data): false|int
{
    $currentDateTime = new DateTime();
    $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
    return file_put_contents(LOGFILE, $formattedDateTime . " [LOGGER] " . $data . PHP_EOL, FILE_APPEND | LOCK_EX);
}