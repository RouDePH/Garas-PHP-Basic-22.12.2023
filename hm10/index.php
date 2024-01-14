<?php

//Написати програму на PHP,
//яка використовує генератор для створення послідовності чисел Фібоначчі,
//які менші за певне значення, передане в функцію.

const LIMIT = 10;

function fibonacciGenerator(int $limit): Generator
{
    $a = 0;
    $b = 1;

    while ($a < $limit) {
        yield $a;
        [$a, $b] = [$b, $a + $b];
    }
}

$fibonacciNumbers = [];

foreach (fibonacciGenerator(LIMIT) as $fibonacciNumber) {
    $fibonacciNumbers[] = $fibonacciNumber;
}

echo implode(", ", $fibonacciNumbers) . PHP_EOL;