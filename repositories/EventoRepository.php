<?php
/**
 * Repository specifico per la tabella 'eventi'.
 */
class EventoRepository extends BaseRepository {
    protected string $table = 'eventi';
    protected string $modelClass = Evento::class;

    /**
     * Crea un nuovo evento solo se l'utente è admin.
     * @param array $data (deve contenere almeno 'utente_id')
     * @return Evento
     * @throws Exception se l'utente non è admin
     */
    public function create(array $data): Evento {
        if (!isset($data['utente_id'])) {
            throw new InvalidArgumentException("utente_id è obbligatorio per creare un evento.");
        }

        // Verifica che l'utente sia admin
        $utenteRepo = new UtenteRepository();
        $utente = $utenteRepo->findById($data['utente_id']);
        if (!$utente) {
            throw new Exception("Utente non trovato.");
        }

        /** @var Evento $evento */
        $evento = parent::create($data);
        return $evento;
    }

    /**
     * Restituisce tutti gli eventi di un dato utente.
     * @param int $utenteId
     * @return array
     */
    public function findByUtenteId(int $utenteId): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE utente_id = :utente_id");
        $stmt->execute([':utente_id' => $utenteId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new $this->modelClass($row), $rows);
    }

    
        /**
     * Restituisce tutti gli eventi associati a una determinata categoria.
     * @param int $categoriaId
     * @return array
     */
    public function findByCategoria(int $categoriaId): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE categoria_id = :categoria_id");
        $stmt->execute([':categoria_id' => $categoriaId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new $this->modelClass($row), $rows);
    }

}
