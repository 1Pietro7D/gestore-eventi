<?php // php database/factories/factory_eventi.php

require_once __DIR__ . '/../Database.php';

class EventoFactory {
    public function generate(int $quantita = 50) {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO eventi (
                titolo, descrizione, data_evento, img_path, video_path, utente_id, 
                categoria_id, massimo_partecipanti, latitudine, longitudine
            ) VALUES (
                :titolo, :descrizione, :data_evento, :img_path, :video_path, :utente_id, 
                :categoria_id, :massimo_partecipanti, :latitudine, :longitudine
            )
        ");

        $categorie = [1, 2, 3, 4, 5]; // Assicurati che esistano
        $utenteId = 1; // Utente fittizio

        for ($i = 1; $i <= $quantita; $i++) {
            $data = [
                'titolo' => "Evento n. $i",
                'descrizione' => "Descrizione generica per l'evento $i, creato a scopo di test.",
                'data_evento' => date('Y-m-d', strtotime("+$i days")),
                'img_path' => '',
                'video_path' => '',
                'utente_id' => $utenteId,
                'categoria_id' => $categorie[array_rand($categorie)],
                'massimo_partecipanti' => rand(10, 100),
                'latitudine' => mt_rand(37000000, 46000000) / 1_000_000,   // tra 37.0 e 46.0
                'longitudine' => mt_rand(8000000, 13000000) / 1_000_000    // tra 8.0 e 13.0
            ];

            $stmt->execute($data);
        }

        echo "$quantita eventi generati con successo.\n";
    }
}

if (php_sapi_name() === 'cli') {
    (new EventoFactory())->generate(50);
}
