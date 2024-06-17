<?php

error_reporting(E_ALL);// вивід усіх помилок
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'autoload.php';



require_once 'app/Router.php';

$router = new app\Router();
$router->run();
