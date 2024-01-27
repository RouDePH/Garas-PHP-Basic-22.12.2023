<?php

abstract class Figure
{
    abstract public function getArea(): float|int;

    abstract public function getPerimeter(): float|int;

    public function showInfo(): void
    {
        $class = new ReflectionClass($this);
        $properties = $class->getProperties(ReflectionProperty::IS_PRIVATE);
        $propertiesArray = [];
        foreach ($properties as $property) {
            $propertiesArray[$property->getName()] = $property->getValue($this);
        }
        $propertiesString = implode(', ', array_map(fn($key, $value) => "$key: $value", array_keys($propertiesArray), $propertiesArray));

        echo get_called_class() . " [ " . $propertiesString . " ]" . PHP_EOL;
        echo get_called_class() . " Area: " . number_format($this->getArea(), 2) . PHP_EOL;
        echo get_called_class() . " Perimeter: " . number_format($this->getPerimeter(), 2) . PHP_EOL;
    }
}