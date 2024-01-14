<?php


//Створити пустий масив і заповнити його випадковими значенняит.
//Порахувати суму елементів масиву.
//Порахувати добуток всіх елементів масиву.
//Перевірте скільки раз число 5 зустрічається у вас в масиві.
//Виведіть на екран тільки числа, які націло діляться на 3.
//Для рішення задач використовувати тільки цикли. (вбудовані функції не використовувати)

const ARRAY_LENGTH = 10;

function getRandArray(int $length, int $min = 1, int $max = 50): array
{
    $array = [];
    for ($i = 0; count($array) < $length; $i++) {
        $array[] = rand($min, $max);
    }
    return $array;
}

$array = getRandArray(ARRAY_LENGTH);
echo "Створити пустий масив і заповнити його випадковими значеннями" . PHP_EOL;
var_dump($array);

function getArraySum(array $array): int|float
{
    $result = 0;
    for ($i = 0; $i < count($array); $i++) {
        $result += $array[$i];
    }
    return $result;
}

$arraySum = getArraySum($array);
echo "Порахувати суму елементів масиву" . PHP_EOL;
var_dump($arraySum);

function getArrayMultiplication(array $array): int|float
{
    $result = 1;
    for ($i = 0; $i < count($array); $i++) {
        $result *= $array[$i];
    }
    return $result > 1 ? $result : 0;
}

$arrayMultiplication = getArrayMultiplication($array);
echo "Порахувати добуток всіх елементів масиву" . PHP_EOL;
var_dump($arrayMultiplication);

function getCountOfRepetitions(array $array, int|float $number = 5): int
{
    $result = 0;
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] === $number) {
            $result++;
        }
    }
    return $result;
}

$countOfRepetitions = getCountOfRepetitions($array);
echo "Перевірте скільки раз число 5 зустрічається у вас в масиві" . PHP_EOL;
var_dump($countOfRepetitions);

function getDivisibleNumbers(array $array, int|float $number = 3): array
{
    $result = [];
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] % $number === 0) {
            $result[] = $array[$i];
        }
    }
    return $result;
}

$divisibleNumbers = getDivisibleNumbers($array);
echo "Виведіть на екран тільки числа, які націло діляться на 3" . PHP_EOL;
var_dump($divisibleNumbers);


