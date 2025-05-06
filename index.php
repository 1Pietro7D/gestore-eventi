<?php
require_once __DIR__ . '/core/Autoloader.php';

session_start();

require_once 'core/Render.php';
$routes = require 'routes.php';
$router = new Router($routes);
$router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);


//php -S localhost:8000