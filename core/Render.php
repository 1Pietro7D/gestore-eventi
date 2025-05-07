<?php

// Funzione view: carica una view e restituisce il contenuto HTML come stringa
function view(string $path, array $data = []): string {
    // Costruisce il percorso completo del file view
    // Esempio: 'utenti.index' diventa '/views/utenti/index.php'
    $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $path) . '.php';

    // Controlla se il file della view esiste
    if (!file_exists($viewFile)) {
        http_response_code(500); // Imposta codice 500 (errore interno)
        return "Errore: view <code>$path</code> non trovata."; // Restituisce messaggio di errore
    }

    // Estrae le variabili dall'array $data in modo che siano disponibili nella view
    // Esempio: ['utente' => $utente] diventa $utente dentro la view
    extract($data);

    // Avvia il buffer di output: cattura tutto l'output del file PHP incluso
    ob_start();

    // Include la view: il contenuto generato sarà catturato dal buffer
    require $viewFile;

    // Restituisce tutto ciò che è stato catturato (il contenuto HTML)
    return ob_get_clean();
}
