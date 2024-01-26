<?php

//Створити клас який має одну властивість $text зі значенням "some text"
// маленькими літерами і один метод print(), який виводить $this->text із заглавної літери ("Some text")
//
//Після цього віднаслідуйтесь від батьківського класу і перезавантажте метод print(),
// щоб він виводив $this->text великими літерами ("SOME TEXT")

class TextPrinter {
    protected string $text = "some text";

    public function print(): void
    {
        echo ucfirst($this->text) . PHP_EOL;
    }
}

class UppercaseTextPrinter extends TextPrinter {
    public function print(): void
    {
        echo strtoupper($this->text) . PHP_EOL;
    }
}


$textPrinter = new TextPrinter();
$textPrinter->print();

$uppercaseTextPrinter = new UppercaseTextPrinter();
$uppercaseTextPrinter->print();