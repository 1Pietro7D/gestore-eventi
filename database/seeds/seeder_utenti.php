<?php // php database/seeds/seeder_utenti.php

require_once __DIR__ . '/../Database.php';

class DatabaseSeeder {
    public function run() {
        $db = Database::connect();

        $stmt = $db->prepare("INSERT INTO utenti (nome, email, password, ruolo, email_confermata) VALUES (?, ?, ?, ?, ?)");

        $users = [
            ['capo', 'pietro.danieli.dev@gmail.com', password_hash('1234', PASSWORD_DEFAULT), 'superadmin', 1],
            ['Mario', 'mario.rossi@example.com', password_hash('password123', PASSWORD_DEFAULT), 'admin', 1],
            ['Luca', 'luca.verdi@example.com', password_hash('mypassword', PASSWORD_DEFAULT), 'user', 1]
        ];

        foreach ($users as $user) {
            $stmt->execute($user);
        }

        echo "Utenti inseriti con successo.\n";
    }
}

// Eseguiamo il seeder solo se richiamato da terminale
if (php_sapi_name() === 'cli') {
    (new DatabaseSeeder())->run();
}
