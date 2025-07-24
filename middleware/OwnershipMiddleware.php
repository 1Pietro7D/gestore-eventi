<?php

require_once __DIR__ . '/MiddlewareInterface.php';

// Middleware che verifica se l'utente è proprietario della risorsa
class OwnershipMiddleware implements MiddlewareInterface {
    private string $controllerName;
    private string $modelName;
    private string $repositoryClass;

    // Riceve il nome dell'handler (es. EventoController@edit)
    public function __construct(string $handler) {
        // Estrae solo il nome del controller
        [$controller,] = explode('@', $handler);
        $this->controllerName = $controller;

        // Rimuove "Controller" per ottenere il nome modello (es. Evento)
        $this->modelName = str_replace('Controller', '', $controller);

        // Costruisce il nome della repository (es. EventoRepository)
        $this->repositoryClass = $this->modelName . 'Repository';
    }

    public function handle(array $request, Closure $next): mixed {
        $user = $_SESSION['user'] ?? null;

        // Se non loggato → errore 401
        if (!$user) {
            http_response_code(401);
            exit("Utente non autenticato.");
        }

       
        // Controlla se la repository esiste
        if (!class_exists($this->repositoryClass)) {
            http_response_code(500);
            exit("Classe repository non trovata: {$this->repositoryClass}");
        }

        // Estrae l'ID dalla richiesta (deve essere presente come parametro)
        $id = $request['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            exit("ID non valido o mancante.");
        }

        // Istanzia la repository e cerca l'oggetto
        $repo = new $this->repositoryClass();
        $oggetto = $repo->findById((int)$id);

        // Se non trovato → errore 404
        if (!$oggetto) {
            http_response_code(404);
            exit("Risorsa non trovata.");
        }

        // Se la risorsa non ha campo utente_id → errore
        if (!isset($oggetto->utente_id)) {
            http_response_code(500);
            exit("La risorsa non ha proprietà 'utente_id'.");
        }
         // Se superadmin → passa sempre
         if ($user['ruolo'] === 'superadmin') {
            return $next($request);
        }

        // Se l'utente NON è il proprietario → errore 403
        if ($oggetto->utente_id != $user['id']) {
            http_response_code(403);
            exit("Non sei il proprietario della risorsa.");
        }

        // Tutto ok → continua
        return $next($request);
    }
}
