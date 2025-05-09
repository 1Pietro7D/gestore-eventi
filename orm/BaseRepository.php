<?php
/**
 * Classe astratta di base per repository.
 */
abstract class BaseRepository implements RepositoryInterface {
    protected PDO $db;                   // Connessione al database
    protected string $table;             // Nome della tabella
    protected string $modelClass;        // Classe del modello da istanziare

    /**
     * Costruttore: inizializza la connessione al database.
     */
    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Restituisce tutti i record come array di oggetti modello.
     * @return array
     */
    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new $this->modelClass($row), $rows);
    }

    /**
     * Trova un record per ID.
     * @param int $id
     * @return object|null
     */
    public function findById(int $id): ?object {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new $this->modelClass($row) : null;
    }

    /**
     * Crea un nuovo record e restituisce il modello.
     * @param array $data
     * @return object
     */
    public function create(array $data): object {
        $columns = array_keys($data);
        $fields = implode(', ', $columns);
        $placeholders = implode(', ', array_map(fn($col) => ":$col", $columns));

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($fields) VALUES ($placeholders)");
        $stmt->execute($this->mapToParams($data));

        return $this->findById((int) $this->db->lastInsertId());
    }

    /**
     * Aggiorna un record per ID.
     * @param int $id
     * @param array $data
     */
    public function updateById(int $id, array $data): void {
        if (empty($data)) return;

        $fields = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $params = $this->mapToParams($data);
        $params[':id'] = $id;

        $sql = "UPDATE {$this->table} SET $fields WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Elimina un record per ID.
     * @param int $id
     */
    public function deleteById(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Mappa un array ['colonna' => 'valore'] a [':colonna' => 'valore'].
     * @param array $data
     * @return array
     */
    protected function mapToParams(array $data): array {
        $params = [];
        foreach ($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }
}
