<?php
/**
 * Interfaccia generica per repository.
 */
interface RepositoryInterface {
    /**
     * Restituisce tutti i record della tabella.
     * @return array
     */
    public function all(): array;

    /**
     * Trova un record per ID.
     * @param int $id
     * @return object|null
     */
    public function findById(int $id): ?object;

    /**
     * Crea un nuovo record.
     * @param array $data
     * @return object
     */
    public function create(array $data): object;

    /**
     * Aggiorna un record esistente per ID.
     * @param int $id
     * @param array $data
     */
    public function updateById(int $id, array $data): void;

    /**
     * Elimina un record per ID.
     * @param int $id
     */
    public function deleteById(int $id): void;
}
