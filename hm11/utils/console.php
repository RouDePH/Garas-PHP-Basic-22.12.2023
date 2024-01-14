<?php

define("STRING_VALIDATOR", fn($input) => strlen($input) > 0);

function getValueFromConsole(string $title, callable $validator): string|int|float
{
    do {
        $result = readline($title);
    } while (!$validator($result));
    return $result;
}