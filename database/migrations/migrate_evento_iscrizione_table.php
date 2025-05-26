<?php // php database/migrations/migrate_evento_iscrizione_table.php // va creata dopo la tabella eventi
require_once __DIR__ . '/../Database.php';

try {
    $db = Database::connect();
    $sql = <<<SQL
CREATE TABLE evento_iscrizioni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evento_id INT NOT NULL,
    utente_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (evento_id, utente_id), -- impedisce doppie iscrizioni
    FOREIGN KEY (evento_id) REFERENCES eventi(id) ON DELETE CASCADE,
    FOREIGN KEY (utente_id) REFERENCES utenti(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

    $db->exec($sql);
    echo "Tabella 'evento_iscrizione' creata con successo.\n";
} catch (PDOException $e) {
    echo "Errore nella migrazione: " . $e->getMessage() . "\n";
}
