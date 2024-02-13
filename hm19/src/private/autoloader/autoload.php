<?php namespace Autoloader;

const AUTOLOADER_DIR = __DIR__ . "/";
require_once AUTOLOADER_DIR . "Psr4AutoloaderClass.php";

$loader = new Psr4AutoloaderClass();
$loader->register();

$loader->addNamespace('Classes', AUTOLOADER_DIR . '../classes');
$loader->addNamespace('Controllers', AUTOLOADER_DIR . '../controllers');
$loader->addNamespace('Routers', AUTOLOADER_DIR . '../routers');
$loader->addNamespace('Interfaces', AUTOLOADER_DIR . '../interfaces');
$loader->addNamespace('Traits', AUTOLOADER_DIR . '../traits');
$loader->addNamespace('JWT', AUTOLOADER_DIR . '../jwt');
$loader->addNamespace('Database', AUTOLOADER_DIR . '../database');