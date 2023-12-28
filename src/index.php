<?php

try {
    $text = "Hello world!";

//    var_dump($text);

    require('./pages/init-page.tpl');
} catch (error) {
    require('./pages/error-page.tpl');
}

