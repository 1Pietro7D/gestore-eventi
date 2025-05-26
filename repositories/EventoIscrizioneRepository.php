<?php

/**
 * Repository per la tabella 'evento_iscrizioni'.
 */
class EventoIscrizioneRepository extends BaseRepository {
    protected string $table = 'evento_iscrizioni';
    protected string $modelClass = EventoIscritto::class;


    /**
     * Conta il numero di iscritti a un evento.
     */
    public function countByEventoId(int $eventoId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE evento_id = ?");
        $stmt->execute([$eventoId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Verifica se un utente è già iscritto a un evento.
     */
    public function exists(int $eventoId, int $utenteId): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE evento_id = ? AND utente_id = ?");
        $stmt->execute([$eventoId, $utenteId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Crea una nuova iscrizione.
     * Richiede: evento_id, utente_id
     */
    public function create(array $data): object {
        return parent::create($data);
    }

    /**
     * Elimina un'iscrizione tramite evento_id e utente_id.
     */
    public function deleteByEventoAndUtente(int $eventoId, int $utenteId): void {
        $stmt = $this->db->prepare("
            DELETE FROM {$this->table}
            WHERE evento_id = :evento_id AND utente_id = :utente_id
        ");
        $stmt->execute([
            ':evento_id' => $eventoId,
            ':utente_id' => $utenteId
        ]);
    }

    /**
     * Restituisce tutti gli ID di eventi a cui è iscritto un utente.
     */
    public function findEventiByUtenteId(int $utenteId): array {
        $stmt = $this->db->prepare("
            SELECT evento_id FROM {$this->table}
            WHERE utente_id = :utente_id
        ");
        $stmt->execute([':utente_id' => $utenteId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'evento_id');
    }

    /**
     * Restituisce tutti gli utenti iscritti a un dato evento.
     * @return Utente[]
     */
    public function findUtentiByEventoId(int $eventoId): array {
        $stmt = $this->db->prepare("
            SELECT u.*
            FROM evento_iscrizioni ei
            JOIN utenti u ON ei.utente_id = u.id
            WHERE ei.evento_id = :evento_id
        ");
        $stmt->execute([':evento_id' => $eventoId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Utente($row), $rows);
    }
    /**
     * Restituisce una mappa evento_id => numero_iscritti per tutti gli eventi.
     * @return array [evento_id => numero]
     */
    public function getIscrittiPerTuttiGliEventi(): array {
        $stmt = $this->db->query("
            SELECT evento_id, COUNT(*) as totale
            FROM {$this->table}
            GROUP BY evento_id
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $mappa = [];
        foreach ($results as $row) {
            $mappa[(int) $row['evento_id']] = (int) $row['totale'];
        }
        return $mappa;
    }
}


// countByEventoId	Quanti iscritti ha un evento
// exists	Verifica se un utente è già iscritto
// create	Crea una nuova iscrizione
// deleteByEventoAndUtente	Annulla una iscrizione
// findEventiByUtenteId	Lista eventi a cui un utente è iscritto
// findUtentiByEventoId	Lista utenti iscritti a un evento