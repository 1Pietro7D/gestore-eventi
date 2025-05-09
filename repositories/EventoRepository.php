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

        if ($utente->ruolo !== 'admin') {
            throw new Exception("Solo gli utenti admin possono creare eventi.");
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
     * Aggiorna l'immagine e/o il video di un evento specifico.
     * @param int $eventoId
     * @param string|null $imgPath
     * @param string|null $videoPath
     */
    public function updateMedia(int $eventoId, ?string $imgPath, ?string $videoPath): void {
        $data = [];
        if ($imgPath !== null) {
            $data['img_path'] = $imgPath;
        }
        if ($videoPath !== null) {
            $data['video_path'] = $videoPath;
        }

        if (!empty($data)) {
            $this->updateById($eventoId, $data);
        }
    }
}
