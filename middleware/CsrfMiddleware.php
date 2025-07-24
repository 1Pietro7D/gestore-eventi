<?php

require_once __DIR__ . '/MiddlewareInterface.php';

// Middleware per protezione CSRF sui form POST
class CsrfMiddleware implements MiddlewareInterface {

    public function handle(array $request, Closure $next): mixed {
        // Solo per richieste POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Estrae il token inviato dal form
            $token = $request['_token'] ?? '';

            // Verifica la validità del token
            if (!Csrf::check($token)) {
                http_response_code(403);
                exit("CSRF token non valido.");
            }
        }

        // Continua con il middleware successivo
        return $next($request);
    }
}
