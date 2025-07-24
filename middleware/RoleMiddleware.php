<?php

require_once __DIR__ . '/MiddlewareInterface.php';

// Middleware che verifica se l'utente ha un ruolo ammesso
class RoleMiddleware implements MiddlewareInterface {
    private array $roles;

    // Accetta un array di ruoli validi (es. ['admin', 'superadmin'])
    public function __construct(array $roles) {
        $this->roles = $roles;
    }

    public function handle(array $request, Closure $next): mixed {
        $user = $_SESSION['user'] ?? null;

        // Se utente non loggato o ruolo non ammesso → errore 403
        if (!$user || !in_array($user['ruolo'], $this->roles)) {
            http_response_code(403);
            exit("Accesso negato. Ruolo non autorizzato.");
        }

        // Continua con il middleware successivo
        return $next($request);
    }
}
