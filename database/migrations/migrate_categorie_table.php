<?php // php database/migrations/migrate_categorie_table.php
require_once __DIR__ . '/../Database.php';

try {
    $db = Database::connect();
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

    $db->exec($sql);
    echo "Tabella 'categorie' creata con successo.\n";
} catch (PDOException $e) {
    echo "Errore nella migrazione: " . $e->getMessage() . "\n";
}
