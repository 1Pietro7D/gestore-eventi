<?php

class Router {
    private array $routes;

    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    public function handle(string $method, ?string $uri): void {
        // Imposta un fallback sicuro
        $uri = $uri ?? $_SERVER['REQUEST_URI'] ?? '/';
        // ❗ Protezione: se URI inizia con doppio slash → 404
        if (!is_string($uri) || preg_match('#^//+#', $uri)) {
            http_response_code(404);
            if (php_sapi_name() !== 'cli') {
                require 'resources/views/error/404.php';
            } else {
                echo "404 - Not Found";
            }
            return;
        }
        
        // Estrai il path e fallback su '/' se fallisce
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';
    
        
    
        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $method, $uri, $params)) {
                $this->dispatch($route, $params, $uri, $method);
                return;
            }
        }
    
        http_response_code(404);
        if (php_sapi_name() !== 'cli') {
            require 'resources/views/error/404.php';
        } else {
            echo "404 - Not Found";
        }
    }
    
    // Verifica se route corrisponde alla URI e al metodo
    private function matchRoute(array $route, string $method, string $uri, ?array &$params): bool {
        if ($route['method'] !== $method) {
            return false;
        }

        // Sostituisce {param} con (?P<param>[^/]+) per cattura nominativa
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<\1>[^/]+)', $route['url']);
        $pattern = '@^' . $pattern . '$@D';

        // Esegue il match
        if (preg_match($pattern, $uri, $matches)) {
            // Estrae solo i parametri nominati
            $params = array_filter(
                $matches,
                fn($key) => is_string($key),
                ARRAY_FILTER_USE_KEY
            );
            return true;
        }

        return false;
    }

    // Esegue la route corrispondente con tutti i middleware
    private function dispatch(array $route, array $params = [], string $uri = '', string $method = ''): void {
        // Include i middleware disponibili
        // require_once __DIR__ . '/../middleware/MiddlewareInterface.php';
        // require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        // require_once __DIR__ . '/../middleware/RoleMiddleware.php';
        // require_once __DIR__ . '/../middleware/CsrfMiddleware.php';
        // require_once __DIR__ . '/../middleware/OwnershipMiddleware.php';

        $middlewareStack = [];

        // Middleware: autenticazione
        if (!empty($route['authorized'])) {
            $middlewareStack[] = new AuthMiddleware();
        }

        // Middleware: controllo ruolo
        if (!empty($route['role'])) {
            $middlewareStack[] = new RoleMiddleware($route['role']);
        }

        // Middleware: CSRF solo per POST
        if ($method === 'POST') {
            $middlewareStack[] = new CsrfMiddleware();
        }

        // Middleware: ownership (solo su rotte che lo richiedono veramente)
        if (!empty($route['owner_check'])) {
            $middlewareStack[] = new OwnershipMiddleware($route['handler']);
        }

        // Funzione finale da eseguire dopo i middleware → il controller
        $run = function ($request) use ($route, $params) {
            [$controllerPath, $methodName] = explode('@', $route['handler'] . '@index');
            require_once __DIR__ . '/../controllers/' . $controllerPath . '.php';

            $controllerClass = basename($controllerPath);
            $controller = new $controllerClass();

            $args = array_values($params);  // Parametri URI come ID ecc.
            $args[] = $request;             // Aggiunge la request finale

            return $controller->$methodName(...$args);
        };

        // 🔁 Merge tra parametri URI (es. id) e dati della richiesta
        $mergedRequest = array_merge($_REQUEST, $params);

        // Costruzione della pipeline dei middleware
        $pipeline = array_reduce(
            array_reverse($middlewareStack),
            fn($next, $middleware) => fn($request) => $middleware->handle($request, $next),
            $run
        );

        // Esecuzione
        echo $pipeline($mergedRequest);
    }
}
