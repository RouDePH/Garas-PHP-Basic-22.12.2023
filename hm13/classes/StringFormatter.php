<?php

class StringFormatter
{
    public static function toFixed(int $number, int $decimals): string
    {
        return number_format($number, $decimals, '.', "");
    }

}