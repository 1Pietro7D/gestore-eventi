<?php
session_start();
require 'core/Router.php';
$routes = require 'routes.php';
$router = new Router($routes);
$router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
