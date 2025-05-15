<?php

// Importa le classi necessarie dal componente Cache di Symfony
use Symfony\Component\Cache\Adapter\FilesystemAdapter; // Per salvare la cache su file
use Symfony\Contracts\Cache\ItemInterface; // Interfaccia per gestire un singolo item della cache

// Autoload delle dipendenze installate con Composer (es. Symfony Cache)
require_once __DIR__ . '/../vendor/autoload.php';

// Definizione della classe CacheMiddleware
class CacheMiddleware {
    // Proprietà privata per gestire il sistema di cache
    private $cache;

    // Costruttore: inizializza il sistema di cache usando il filesystem
    public function __construct() {
        $this->cache = new FilesystemAdapter(); // Usa la directory di default per salvare i file di cache
    }

    /**
     * Metodo per gestire il caching di un contenuto.
     * 
     * @param string   $key      Chiave univoca per identificare il contenuto nella cache
     * @param int      $ttl      Durata della cache in secondi (TTL = Time To Live)
     * @param callable $callback Funzione che genera il contenuto da memorizzare se non è già in cache
     * 
     * @return string  Contenuto generato (dalla cache o dalla funzione) con un commento di debug
     */
    public function handle(string $key, int $ttl, callable $callback): string {
        $fromCache = true; // Flag per sapere se il contenuto viene dalla cache

        // Recupera o genera il contenuto associato alla chiave $key
        $content = $this->cache->get($key, function (ItemInterface $item) use ($ttl, $callback, &$fromCache) {
            // Questo blocco viene eseguito solo se il contenuto NON è già presente in cache
            $fromCache = false; // Il contenuto è stato appena generato

            // Imposta la durata della cache (TTL)
            $item->expiresAfter($ttl);

            // Esegue il callback e cattura l'output
            ob_start();          // Inizia la memorizzazione dell'output
            $callback();         // Esegue la funzione fornita
            return ob_get_clean(); // Restituisce l'output e termina il buffer
        });

        // Aggiunge un commento HTML per indicare se il contenuto è stato caricato dalla cache o rigenerato
        $debugNote = $fromCache
            ? "\n<!-- Caricato dalla cache -->"
            : "\n<!-- Caricato dal database -->";

        // Restituisce il contenuto completo del commento di debug
        return $content . $debugNote;
    }

    /**
     * Metodo per cancellare un elemento specifico dalla cache
     * 
     * @param string $key Chiave dell'elemento da eliminare
     */
    public function clear(string $key){
        $this->cache->deleteItem($key); // Elimina l'item dalla cache
    }
}
