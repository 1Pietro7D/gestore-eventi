<?php

// Interfaccia che ogni middleware deve implementare
interface MiddlewareInterface {

    /**
     * Metodo obbligatorio per ogni middleware.
     * Prende la richiesta corrente e una closure $next (il prossimo passo nella catena).
     * Deve restituire mixed: può essere una risposta HTML, JSON o altro.
     */
    public function handle(array $request, Closure $next): mixed;
}
