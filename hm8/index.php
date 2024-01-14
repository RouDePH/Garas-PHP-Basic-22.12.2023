<?php

//Виведіть на екран всі числа від 1 до 10 використовуючи цикл while
//Обчисліть факторіал числа 5 використовуючи цикл while.
//Виведіть на екран всі парні числа від 1 до 20 використовуючи цикл while.

function run(callable $body, closure $validator, int $startFrom = 1): array
{
    $result = [];
    $i = $startFrom;

    while ($validator($i)) {
        $bodyResult = $body($i);
        if ($bodyResult !== null) {
            $result[] = $bodyResult;
        }
        $i++;
    }
    return $result;
}

$factorial = function ($n) {
    global $factorial;
    if ($n == 0 || $n == 1) {
        return 1;
    } else {
        return $n * $factorial($n - 1);
    }
};

$firstTaskResult = run(function ($i) {
    return $i;
}, fn($number) => $number <= 10);
var_dump(implode(", ", $firstTaskResult));

$secondTaskResult = run($factorial, fn($number) => $number <= 5);
var_dump(end($secondTaskResult));

$thirdTaskResult = run(function ($i) {
    if ($i % 2 === 0) {
        return $i;
    } else return null;
}, fn($number) => $number <= 20);
var_dump(implode(', ', $thirdTaskResult));


