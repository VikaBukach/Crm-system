<?php


require_once 'local-config.php';
require_once 'autoload.php';
require_once 'app/Router.php';

$router = new app\Router();
$router->run();
