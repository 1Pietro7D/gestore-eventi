<?php
/**
 * Repository specifico per la tabella 'utenti'.
 */
class UtenteRepository extends BaseRepository {
    protected string $table = 'utenti';
    protected string $modelClass = Utente::class;

    /**
     * Trova un utente per email.
     * @param string $email
     * @return Utente|null
     */
    public function findByEmail(string $email): ?Utente {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new $this->modelClass($row) : null;
    }

    /**
     * Crea un nuovo utente hashando la password prima di salvare.
     * @param array $data
     * @return Utente
     */
    public function create(array $data): Utente {
        if (isset($data['password'])) {
            // Hash la password prima di chiamare il create base
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        /** @var Utente $utente */
        $utente = parent::create($data);
        return $utente;
    }
}
