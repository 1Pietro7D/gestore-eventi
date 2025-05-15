<?php

// Classe Router per gestire le rotte dell'applicazione
class Router {
    private array $routes;
    private string $basePath = '/gestore-eventi';

    public function __construct(array $routes) {
        $this->routes = $this->prepareRoutes($routes);
    }

    public function handle(string $method, string $uri) {
        $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';

        if (str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath)) ?: '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                return $this->dispatch($route, $matches, $uri, $method);
            }
        }

        http_response_code(404);
        if (php_sapi_name() !== 'cli') {
            require 'views/404.php';
        } else {
            echo "404 - Not Found";
        }
    }

    private function dispatch(array $route, array $params = [], string $uri = '', string $method = '') {
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

        $params = empty($params) ? [$_REQUEST] : array_merge($params, [$_REQUEST]);

        // Usa la cache solo per la homepage
        if ($method === 'GET' && $uri === '/') {
            // require_once __DIR__ . '/CacheMiddleware.php';
            $cache = new CacheMiddleware();
            echo $cache->handle('home_cache', 60, function () use ($controller, $methodName, $params) {
                echo $controller->$methodName(...$params);
            });
            return;
        }       
        $result = $controller->$methodName(...$params);

        if ($result === null) {
            http_response_code(404);
            if (php_sapi_name() !== 'cli') {
                require 'views/404.php';
                exit;
            } else {
                echo "404 - Not Found";
            }
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
