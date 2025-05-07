<?php
class UtenteRepository extends BaseRepository {
    protected string $table = 'utenti';
    protected string $modelClass = Utente::class;

    public function findByEmail(string $email): ?Utente {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new $this->modelClass($row) : null;
    }

    public function create(array $data): Utente {
        // Hash la password prima di chiamare il create base
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $utente = parent::create($data);
        return $utente;
    }
}
