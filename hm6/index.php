<?php

//Написати програму на PHP, яка містить іменовану функцію, що приймає три аргументи:
//
//два обовʼязкових типу int і третій необовʼязковий типу closure
//
//Функція має повертати результат множення першого і другого аргументів,
// а в разі передачі третього аргументу (анонімної функції),
// перед return має викликати анонімну функції і передати в неї результат обчислення.
//
//Реалізувати аноннімну функцію, яка виводить на екран переданий аргумент.

$print = function ($value) {
    echo "Result: " . $value . PHP_EOL;
};

function multiply(int $firstValue, int $secondValue, ?closure $func = null): int
{
    $result = $firstValue * $secondValue;
    if (isset($func)) {
        $func($result);
    }
    return $result;
}

multiply(2, 5, $print);