<?php
require_once __DIR__ . '/core/AutoLoader.php';
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();
require_once 'core/SecurityHeaders.php';
setSecurityHeaders();

Csrf::init();
require_once 'core/Render.php';
$routes = require 'routes.php';
$router = new Router($routes);
$router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);


//php -S localhost:8000