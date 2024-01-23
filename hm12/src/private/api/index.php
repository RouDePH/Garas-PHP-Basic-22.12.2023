<?php
const API_PATH = __DIR__ . "/";
const PRIVATE_ROOT_PATH = __DIR__ . "/../";

require_once API_PATH . '../classes/Router.php';
require_once API_PATH . '../routers/TaskRouter.php';

//Насколько спорное решение по шкале от 1 до 10?
$router = new Router();
TaskRouter::registerRoutes("/task", $router);