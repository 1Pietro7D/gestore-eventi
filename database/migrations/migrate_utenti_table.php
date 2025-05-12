<?php // php database/migrations/migrate_utenti_table.php
require_once __DIR__ . '/../Database.php';

try {
    $db = Database::connect();
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ruolo ENUM('user', 'admin', 'superadmin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

    $db->exec($sql);
    echo "Tabella 'utenti' creata con successo.\n";
} catch (PDOException $e) {
    echo "Errore nella migrazione: " . $e->getMessage() . "\n";
}
