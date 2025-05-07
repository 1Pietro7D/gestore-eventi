<?php

class Router {
    private array $routes;
    private string $basePath = '/gestore-eventi';

    public function __construct(array $routes) {
        $this->routes = $this->prepareRoutes($routes);
    }

    public function handle(string $method, string $uri) {
        $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';

        // Rimuove il prefisso della sotto-cartella
        if (str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
            $uri = $uri ?: '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Rimuove il match completo
                return $this->dispatch($route, $matches);
            }
        }

        http_response_code(404);
        require 'views/404.php';
    }

    private function dispatch(array $route, array $params = []) {
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

        [$controllerPathPart, $methodName] = explode('@', $route['handler'] . '@index');

        $controllerPath = __DIR__ . '/../controllers/' . $controllerPathPart . '.php';

        if (!file_exists($controllerPath)) {
            http_response_code(500);
            echo "Controller file not found: $controllerPath";
            return;
        }

        require_once $controllerPath;

        $controllerClassName = basename($controllerPathPart);

        if (!class_exists($controllerClassName)) {
            http_response_code(500);
            echo "Class $controllerClassName not found in $controllerPath";
            return;
        }

        $controller = new $controllerClassName;

        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            echo "Method $methodName not found in $controllerClassName";
            return;
        }

        if (empty($params)) {
            // es: /utenti -> solo request
            $params = [$_REQUEST];
        } else {
            // es: /utenti/update/7 -> request + id 
            $params[] = $_REQUEST;
        }
        $result = $controller->$methodName(...$params);
        

        if ($result === null) {
            http_response_code(404);
            require 'views/404.php';
            exit;
        }

        echo $result;
        return;
    }

    private function prepareRoutes(array $routes): array {
        foreach ($routes as &$route) {
            if (!isset($route['pattern'])) {
                $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route['url']);
                $route['pattern'] = '#^' . $pattern . '$#';
            }
        }
        return $routes;
    }
}
