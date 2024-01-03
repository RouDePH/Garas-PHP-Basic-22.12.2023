<?php

//Написати програму на PHP, яка виводить на екран назву кольору в залежності від стану змінної $value
//
//Залежності значення змінної і кольору:
//- 1 - green
//- 2 - red
//- 3 - blue
//- 4 - brown
//- 5 - violet
//- 6 - black
//Всі інші значення мають виводити white

function getValueFromConsole(string $title, callable $validator): int
{
    do {
        $result = readline($title);
    } while (!$validator($result));
    return $result;
}

$numberValidator = fn($input) => is_numeric($input);

$value = getValueFromConsole("Enter value: ", $numberValidator);

$color = match ($value) {
    1 => "green",
    2 => "red",
    3 => "blue",
    4 => "brown",
    5 => "violet",
    6 => "black",
    default => "white"
};

echo "$value = $color" . PHP_EOL;



