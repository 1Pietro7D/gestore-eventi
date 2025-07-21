<?php

// Registra una funzione di autoload che viene eseguita ogni volta che PHP trova una classe non ancora caricata
spl_autoload_register(function ($class) {

    // Definisce le cartelle in cui cercare i file delle classi
    $paths = [
        __DIR__ . '/../controllers/',   // Cartella dei controller
        __DIR__ . '/../models/',        // Cartella dei modelli (Model)
        __DIR__ . '/../repositories/',  // Cartella delle repository (es: UtenteRepository)
        __DIR__ . '/../core/',          // Cartella dei file core (es: Router, Autoloader stesso)
        __DIR__ . '/../database/',      // Cartella per la gestione del database
        __DIR__ . '/../orm/',           // Cartella del orm 
        __DIR__ . '/../utils/',         // Cartella con file generici (es: Gmail, Cart)
    ];

    // Scorre ciascun percorso definito sopra
    foreach ($paths as $path) {
        // Costruisce il percorso completo al file PHP della classe
        $file = $path . $class . '.php';

        // Se il file esiste, lo include una sola volta
        if (file_exists($file)) {
            require_once $file;
            return; // Una volta trovato e caricato, esce dal ciclo
        }
    }
});
