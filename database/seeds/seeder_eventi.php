<?php // php database/seeds/seeder_eventi.php

require_once __DIR__ . '/../Database.php';

class DatabaseSeeder {
    public function run() {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO eventi (
                titolo, descrizione, data_evento, img_path, video_path, utente_id, 
                categoria_id, massimo_partecipanti, latitudine, longitudine, prezzo
            ) VALUES (
                :titolo, :descrizione, :data_evento, :img_path, :video_path, :utente_id, 
                :categoria_id, :massimo_partecipanti, :latitudine, :longitudine, :prezzo
            )
        ");

        $eventi = [
            [
                'titolo' => 'Workshop di Fotografia',
                'descrizione' => 'Corso intensivo di fotografia naturalistica.',
                'data_evento' => '2025-06-10',
                'img_path' => '',
                'video_path' => '',
                'utente_id' => 1,
                'categoria_id' => 1,
                'massimo_partecipanti' => 50,
                'latitudine' => 45.4642,
                'longitudine' => 9.1900,
                'prezzo' => 39.90
            ],
            [
                'titolo' => 'Corso di Cucina Vegana',
                'descrizione' => 'Scopri i segreti della cucina vegana con chef professionisti.',
                'data_evento' => '2025-07-01',
                'img_path' => '',
                'video_path' => '',
                'utente_id' => 1,
                'categoria_id' => 2,
                'massimo_partecipanti' => 50,
                'latitudine' => 41.9028,
                'longitudine' => 12.4964,
                'prezzo' => 29.00
            ],
            [
                'titolo' => 'Seminario di Crescita Personale',
                'descrizione' => 'Tecniche per migliorare l\'autostima e raggiungere i propri obiettivi.',
                'data_evento' => '2025-07-15',
                'img_path' => '',
                'video_path' => '',
                'utente_id' => 1,
                'categoria_id' => 3,
                'massimo_partecipanti' => 50,
                'latitudine' => 43.7696,
                'longitudine' => 11.2558,
                'prezzo' => 49.00
            ],
            [
                'titolo' => 'Corso di Programmazione PHP',
                'descrizione' => 'Impara le basi di PHP e crea i tuoi primi progetti.',
                'data_evento' => '2025-08-20',
                'img_path' => '',
                'video_path' => '',
                'utente_id' => 1,
                'categoria_id' => 5,
                'massimo_partecipanti' => 50,
                'latitudine' => 45.0703,
                'longitudine' => 7.6869,
                'prezzo' => 59.00
            ]
        ];

        foreach ($eventi as $evento) {
            $stmt->execute($evento);
        }

        echo "Eventi inseriti con successo.\n";
    }
}

if (php_sapi_name() === 'cli') {
    (new DatabaseSeeder())->run();
}
