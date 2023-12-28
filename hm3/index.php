<?php

//Написати програму на PHP, яка порівнює різні типи даних
// (цілі числа, рядки, логічні значення та інші) за допомогою операторів суворого і несуворого порівняння та виводить результат порівняння на екран.
//
//В програмі потрібно використати мінімум п'ять різних типів даних та мінімум по два порівняння (з використанням суворого та несуворого порівняння).
//
//Використати приведення типів і повторити суворе і несуворе порівняння

$integerValue = 10;
$floatValue = 10.0;
$stringValue = "10";
$booleanValue = true;
$nullValue = null;

echo "Strict Comparison:\n";
var_dump($integerValue === $floatValue);
var_dump($integerValue === $stringValue);
var_dump($booleanValue === $integerValue);
var_dump($nullValue === $booleanValue);
echo "\n";

echo "Non-strict Comparison:\n";
var_dump($integerValue == $floatValue);
var_dump($integerValue == $stringValue);
var_dump($booleanValue == $integerValue);
var_dump($nullValue == $booleanValue);
echo "\n";

echo "Type Casting with Strict Comparison:\n";
var_dump($integerValue === (int)$floatValue);
var_dump($stringValue === (string)$integerValue);
var_dump($booleanValue === (bool)$integerValue);
var_dump((bool)$nullValue === $booleanValue);
echo "\n";

echo "Type Casting with Non-strict Comparison:\n";
var_dump($integerValue == (int)$floatValue);
var_dump($stringValue == (string)$integerValue);
var_dump($booleanValue == (bool)$integerValue);
var_dump((bool)$nullValue == $booleanValue);
echo "\n";