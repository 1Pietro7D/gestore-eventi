<?php
class UtenteRepository {
    protected $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM utenti");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Utente($row), $rows);
    }
    public function findById(int $id): ?Utente {
        $stmt = $this->db->prepare("SELECT * FROM utenti WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Utente($row) : null;
    }
    
    public function findByEmail(string $email): ?Utente {
        $stmt = $this->db->prepare("SELECT * FROM utenti WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Utente($row) : null;
    }

    public function deleteById(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM utenti WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function create(array $data): Utente {
        $stmt = $this->db->prepare("INSERT INTO utenti (nome, email, password, ruolo) VALUES (:nome, :email, :password, :ruolo)");
        $stmt->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':ruolo' => $data['ruolo'] ?? 'user',
        ]);

        return $this->findByEmail($data['email']);
    }
    public function updateById(int $id, array $data): void {
        $fields = [];
        $params = [':id' => $id];
    
        if (isset($data['nome'])) {
            $fields[] = 'nome = :nome';
            $params[':nome'] = $data['nome'];
        }
    
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params[':email'] = $data['email'];
        }
    
        if (isset($data['password']) && $data['password'] !== '') {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
    
        if (isset($data['ruolo'])) {
            $fields[] = 'ruolo = :ruolo';
            $params[':ruolo'] = $data['ruolo'];
        }
    
        if (!empty($fields)) {
            $sql = "UPDATE utenti SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
        }
    }
    
}
