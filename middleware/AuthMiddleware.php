<?php

require_once __DIR__ . '/MiddlewareInterface.php';

// Middleware che verifica se l'utente è autenticato
class AuthMiddleware implements MiddlewareInterface {

    public function handle(array $request, Closure $next): mixed {
        // Se l'utente non è loggato, blocca con errore 401
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            exit("Non autorizzato. Effettua il login.");
        }

        // Altrimenti continua con il middleware successivo
        return $next($request);
    }
}
