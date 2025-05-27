<?php // php database/migrations/migrate_otp_verifiche_table.php // va creata dopo la tabella utenti
require_once __DIR__ . '/../Database.php';

try {
    $db = Database::connect();
    $sql = <<<SQL
CREATE TABLE otp_verifiche (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    codice VARCHAR(6) NOT NULL,
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_utente FOREIGN KEY (user_id) REFERENCES utenti(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

    $db->exec($sql);
    echo "Tabella 'otp_verifiche' creata con successo.\n";
} catch (PDOException $e) {
    echo "Errore nella migrazione: " . $e->getMessage() . "\n";
}
