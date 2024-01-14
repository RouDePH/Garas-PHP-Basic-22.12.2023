<?php

const ARRAY_LENGTH = 10;

function getRandArray(int $length, int $min = 1, int $max = 50): array
{
    $array = [];
    for ($i = 0; count($array) < $length; $i++) {
        $array[] = rand($min, $max);
    }
    return $array;
}

function sortArray(array $array): array
{
    sort($array);
    return $array;
}

$getMinValueFromArray = fn(array $array): mixed => min($array);
$getMaxValueFromArray = fn(array $array): mixed => max($array);

$array = getRandArray(ARRAY_LENGTH);
var_dump($array);

$maxValue = $getMaxValueFromArray($array);
$minValue = $getMinValueFromArray($array);
var_dump($maxValue, $minValue);

$sortedArray = sortArray($array);
var_dump($sortedArray);
