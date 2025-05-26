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

    public function paginate(int $perPagina = 15, string $baseUrl = ""): array {
        $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        $offset = ($pagina - 1) * $perPagina;

        // Totale record
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        $total = (int) $stmt->fetchColumn();
        $lastPage = (int) ceil($total / $perPagina);

        // Record paginati
        $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $perPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = array_map(fn($row) => new $this->modelClass($row), $rows);

        // Path base per URL
        $baseUrl = $baseUrl ?? self::currentUrlWithoutQuery(['pagina']);

        return [
            'total' => $total,
            'per_page' => $perPagina,
            'current_page' => $pagina,
            'last_page' => $lastPage,
            'first_page_url' => "{$baseUrl}?pagina=1",
            'last_page_url' => "{$baseUrl}?pagina={$lastPage}",
            'next_page_url' => $pagina < $lastPage ? "{$baseUrl}?pagina=" . ($pagina + 1) : null,
            'prev_page_url' => $pagina > 1 ? "{$baseUrl}?pagina=" . ($pagina - 1) : null,
            'path' => $baseUrl,
            'from' => $total > 0 ? ($offset + 1) : null,
            'to' => $total > 0 ? min($offset + $perPagina, $total) : null,
            'data' => $data,
        ];
    }
    protected static function currentUrlWithoutQuery(array $except = []): string {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        
        // Rimuove solo i parametri specificati (es. ?pagina)
        $query = $_GET;
        foreach ($except as $key) {
            unset($query[$key]);
        }
    
        $queryString = http_build_query($query);
        $url = "{$scheme}://{$host}{$uri}";
        if (!empty($queryString)) {
            $url .= '?' . $queryString;
        }
    
        return $url;
    }

}
