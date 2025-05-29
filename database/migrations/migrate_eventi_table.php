<?php
// php database/migrations/migrate_eventi_table.php
// Va creata dopo la tabella categorie

require_once __DIR__ . '/../Database.php';

try {
    $db = Database::connect();
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS eventi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(255) NOT NULL,
    descrizione TEXT,
    data_evento DATE NOT NULL,
    img_path VARCHAR(255),
    video_path VARCHAR(255),
    utente_id INT NOT NULL,
    categoria_id INT NOT NULL,
    massimo_partecipanti INT NOT NULL DEFAULT 100,
    latitudine DECIMAL(10,8),
    longitudine DECIMAL(11,8),
    prezzo DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utente_id) REFERENCES utenti(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorie(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

    $db->exec($sql);
    echo "Tabella 'eventi' creata con successo.\n";
} catch (PDOException $e) {
    echo "Errore nella migrazione: " . $e->getMessage() . "\n";
}
