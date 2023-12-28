<?php

//Написати консольну програму на PHP, яка робить кілька дій:
//
//- запитує імʼя і виводить в консоль кілька рядків привітання по імені
//
//- приймає кілька числових аргументів і виводить їх суму та середне арифметичне

function getValueFromConsole(string $title, callable $validator): string | int | float
{
    do {
        $result = readline($title);
    } while (!$validator($result));
    return $result;
}

$nameValidator = fn($input) => strlen($input) > 0;
$numberValidator = fn($input) => is_numeric($input);

$name = getValueFromConsole("Enter your name: ", $nameValidator);
echo "Hello $name!\n";

$firstNumber = getValueFromConsole("Enter first number: ", $numberValidator);
$secondNumber = getValueFromConsole("Enter second number: ", $numberValidator);

echo "$firstNumber + $secondNumber = ".($firstNumber + $secondNumber)."\n";