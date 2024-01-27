<?php const APP_DIR = __DIR__ . "/";

require_once APP_DIR . "classes/Rectangle.php";
require_once APP_DIR . "classes/Circle.php";

try {
    $rectangle = new Rectangle(4, 5);
    $rectangle->showInfo();

    $circle = new Circle(7);
    $circle->showInfo();

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}