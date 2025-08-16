<?php
// Eseguire da CLI come: php database/migrations/migrate_canzoni_table.php
require_once __DIR__ . '/../Database.php'; // include helper connessione

try { // blocco try per gestire errori
    $db = Database::connect(); // apre connessione PDO
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS canzoni (
    id INT AUTO_INCREMENT PRIMARY KEY, -- chiave primaria
    utente_id INT NOT NULL,            -- proprietario (per owner_check)
    titolo VARCHAR(255) NOT NULL,      -- titolo canzone
    artista VARCHAR(255) NOT NULL,     -- artista/gruppo
    youtube_url TEXT NOT NULL,         -- url youtube completo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- creato il
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- aggiornato il
    CONSTRAINT fk_canzoni_utente FOREIGN KEY (utente_id) REFERENCES utenti(id) ON DELETE CASCADE -- fk utente
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL; // fine heredoc
    $db->exec($sql); // esegue SQL
    echo "Tabella 'canzoni' creata/ok.
"; // messaggio ok
} catch (PDOException $e) { // gestione eccezione
    echo 'Errore migrazione canzoni: ' . $e->getMessage() . "
"; // stampa errore
}
?>