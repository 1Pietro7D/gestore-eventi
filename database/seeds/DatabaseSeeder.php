<?php // php database/seeds/DatabaseSeeder.php

require_once __DIR__ . '/../Database.php';

class DatabaseSeeder {
    public function run() {
        $db =Database::connect();

        $stmt = $db->prepare("INSERT INTO utenti (nome, email, password, ruolo) VALUES (?, ?, ?, ?)");

        $users = [
            ['Mario', 'mario.rossi@example.com', password_hash('password123', PASSWORD_DEFAULT), 'admin'],
            ['Luca', 'luca.verdi@example.com', password_hash('mypassword', PASSWORD_DEFAULT), 'user']
        ];

        foreach ($users as $user) {
            $stmt->execute($user);
        }

        echo "Utenti inseriti con successo.
";
    }
}

// Eseguiamo il seeder solo se richiamato da terminale
if (php_sapi_name() === 'cli') {
    (new DatabaseSeeder())->run();
}
