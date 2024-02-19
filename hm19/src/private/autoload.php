<?php

use Autoloader\Psr4AutoloaderClass;

const PRIVATE_AUTOLOADER_DIR = __DIR__ . "/";
require_once PRIVATE_AUTOLOADER_DIR . "autoloader/Psr4AutoloaderClass.php";

$loader = new Psr4AutoloaderClass();
$loader->register();

$loader->addNamespace('Classes', PRIVATE_AUTOLOADER_DIR . 'classes');
$loader->addNamespace('Controllers', PRIVATE_AUTOLOADER_DIR . 'controllers');
$loader->addNamespace('Routers', PRIVATE_AUTOLOADER_DIR . 'routers');
$loader->addNamespace('Interfaces', PRIVATE_AUTOLOADER_DIR . 'interfaces');
$loader->addNamespace('Traits', PRIVATE_AUTOLOADER_DIR . 'traits');
$loader->addNamespace('JWT', PRIVATE_AUTOLOADER_DIR . 'jwt');
$loader->addNamespace('Models', PRIVATE_AUTOLOADER_DIR . 'models');
$loader->addNamespace('Views', PRIVATE_AUTOLOADER_DIR . 'views');
$loader->addNamespace('Utils', PRIVATE_AUTOLOADER_DIR . 'utils');