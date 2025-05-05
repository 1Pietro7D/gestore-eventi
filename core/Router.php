<?php

class Router {
    private array $routes;
    private string $basePath = '/mini3';

    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    public function handle(string $method, string $uri) {
        $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';

        // Rimuove il prefisso della sotto-cartella
        if (str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
            $uri = $uri ?: '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['url'] === $uri) {
                return $this->dispatch($route);
            }
        }

        http_response_code(404);
        require 'views/404.php';
        return;
    }

    private function dispatch(array $route) {
        if (!empty($route['authorized'])) {
            if (!isset($_SESSION['user'])) {
                http_response_code(401);
                echo "Non autorizzato (utente non loggato)";
                return;
            }

            if (!empty($route['role']) && !in_array($_SESSION['user']['ruolo'], $route['role'])) {
                http_response_code(403);
                echo "Accesso negato (ruolo insufficiente)";
                return;
            }
        }

        [$controllerName, $methodName] = explode('@', $route['handler']);
        $controllerPath = "controllers/$controllerName.php";

        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controller = new $controllerName;
            if (method_exists($controller, $methodName)) {
                return $controller->$methodName($_REQUEST);
            }
        }

        http_response_code(500);
        echo "Handler not found";
    }
}
