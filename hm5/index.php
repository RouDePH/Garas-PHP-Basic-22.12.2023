<?php

//1. Написати програму на PHP, яка містить користувацьку функцію
// для обчислення площі кола та демонструє використання передачі
// даних у функцію за допомогою параметрів.
//
//2. Написати програму на PHP, яка приймає число і підносить його до ступеню
//
//3. Використайте функцію в двох варіантах: коли вона повертає нове число і змінює передане.

function power(int|float $number, int|float $exponent = 2): int|float
{
    return $number ** $exponent;
}

function calculateSquare(int|float $radius): int|float
{
    return M_PI * power($radius);
}

function calculateSquareLinked(int|float &$value): void
{
    $value = M_PI * power($value);
}

$result = calculateSquare(5);
echo "Result of first function: " . number_format($result, 2) . PHP_EOL;

$value = 5;
calculateSquareLinked($value);
echo "Result of second function: " . number_format($value, 2) . PHP_EOL;