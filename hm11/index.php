<?php

//1. Написати програму на PHP, яка приймає з консолі аргументи, які введені, і записує їх в файл
//2. Написати іншу програму, яка виводить з файлу логу останні аргументи попередньої програми, які були введені.

const ABSOLUTE_PATH = __DIR__ . "/";

require_once ABSOLUTE_PATH . "utils/logger.php";
require_once ABSOLUTE_PATH . "utils/console.php";

$data = getValueFromConsole("Enter data: ", STRING_VALIDATOR);

$isSuccess = writeLog($data);
if ($isSuccess !== false) showLogs();

