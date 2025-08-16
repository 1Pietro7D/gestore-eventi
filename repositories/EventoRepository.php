<?php
/**
 * Repository specifico per la tabella 'eventi'.
 */
class EventoRepository extends BaseRepository {
    protected string $table = 'eventi';
    protected string $modelClass = Evento::class;

    /**
     * Crea un nuovo evento solo se l'utente � admin.
     * @param array $data (deve contenere almeno 'utente_id')
     * @return Evento
     * @throws Exception se l'utente non � admin
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
    * Applica un filtro per selezionare solo gli eventi di una determinata categoria.
    * Da usare in catena con get() o paginate().
    *
    * Esempio: $repo->findByCategoria(5)->paginate(10);
    * oppure: $repo->findByCategoria(5)->get();
    * @param int $categoriaId ID della categoria
    * @return static Istanza del repository con filtro applicato
    */
    public function findByCategoria(int $categoriaId): static {
        return $this->where('categoria_id', $categoriaId);
    }

}
