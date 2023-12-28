<?php

try {
    $text = "Hello world!";

    require('./pages/init-page.tpl');
} catch (error) {
    require('./pages/error-page.tpl');
}

