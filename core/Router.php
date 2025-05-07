<?php

// Classe Router per gestire le rotte dell'applicazione
class Router {
    private array $routes; // Array delle rotte disponibili
    private string $basePath = '/gestore-eventi'; // Prefisso della cartella base (utile se l'app è in una sottocartella)

    // Costruttore: riceve le rotte e le prepara
    public function __construct(array $routes) {
        $this->routes = $this->prepareRoutes($routes); // Prepara i pattern regex delle rotte dinamiche
    }

    // Gestisce una richiesta HTTP (es. GET /utenti/5)
    public function handle(string $method, string $uri) {
        // Pulisce l'URI (rimuove slash finali o imposta '/' se vuoto)
        $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';

        // Rimuove il prefisso basePath se l'app è in una sottocartella
        if (str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
            $uri = $uri ?: '/';
        }

        // Scorre tutte le rotte e cerca quella corrispondente
        foreach ($this->routes as $route) {
            // Controlla se metodo (GET, POST, ecc.) e pattern dell'URL corrispondono
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Rimuove il primo match (l'intera stringa)
                return $this->dispatch($route, $matches); // Invia alla funzione dispatch con i parametri estratti
            }
        }

        // Nessuna rotta trovata: mostra la pagina 404
        http_response_code(404);
        require 'views/404.php';
    }

    // Esegue il controller e il metodo associato alla rotta trovata
    private function dispatch(array $route, array $params = []) {
        // Se la rotta richiede autorizzazione, controlla la sessione utente
        if (!empty($route['authorized'])) {
            if (!isset($_SESSION['user'])) {
                http_response_code(401); // Non autorizzato
                echo "Non autorizzato (utente non loggato)";
                return;
            }

            // Se è richiesto un ruolo specifico, verifica che l'utente ce l'abbia
            if (!empty($route['role']) && !in_array($_SESSION['user']['ruolo'], $route['role'])) {
                http_response_code(403); // Accesso negato
                echo "Accesso negato (ruolo insufficiente)";
                return;
            }
        }

        // Divide la stringa handler (es. 'UtenteController@show') in controller e metodo
        [$controllerPathPart, $methodName] = explode('@', $route['handler'] . '@index');

        // Costruisce il percorso fisico del file controller
        $controllerPath = __DIR__ . '/../controllers/' . $controllerPathPart . '.php';

        // Se il file del controller non esiste, mostra errore 500
        if (!file_exists($controllerPath)) {
            http_response_code(500);
            echo "Controller file not found: $controllerPath";
            return;
        }

        // Include il file del controller
        require_once $controllerPath;

        // Estrae il nome della classe (es. 'UtenteController' da 'UtenteController.php')
        $controllerClassName = basename($controllerPathPart);

        // Controlla che la classe esista
        if (!class_exists($controllerClassName)) {
            http_response_code(500);
            echo "Class $controllerClassName not found in $controllerPath";
            return;
        }

        // Crea un'istanza del controller
        $controller = new $controllerClassName;

        // Controlla che il metodo esista nel controller
        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            echo "Method $methodName not found in $controllerClassName";
            return;
        }

        // Se non ci sono parametri dinamici (es: /utenti), passa solo $_REQUEST
        if (empty($params)) {
            $params = [$_REQUEST];
        } else {
            // Se ci sono parametri dinamici (es: /utenti/update/7), aggiunge $_REQUEST alla fine
            $params[] = $_REQUEST;
        }

        // Esegue il metodo del controller con i parametri
        $result = $controller->$methodName(...$params);

        // Se il metodo non restituisce nulla, mostra la pagina 404 (fallback)
        if ($result === null) {
            http_response_code(404);
            require 'views/404.php';
            exit;
        }

        // Mostra il risultato della view (se esiste)
        echo $result;
        return;
    }

    // Prepara le rotte: trasforma le rotte dinamiche (es. /utenti/{id}) in pattern regex
    private function prepareRoutes(array $routes): array {
        foreach ($routes as &$route) {
            if (!isset($route['pattern'])) {
                // Sostituisce {param} con una regex che cattura qualsiasi cosa tranne lo slash
                $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route['url']);
                $route['pattern'] = '#^' . $pattern . '$#';
            }
        }
        return $routes;
    }
}
