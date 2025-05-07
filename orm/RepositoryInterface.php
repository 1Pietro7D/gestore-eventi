<?php
interface RepositoryInterface {
    public function all(): array;
    public function findById(int $id);
    public function create(array $data);
    public function updateById(int $id, array $data): void;
    public function deleteById(int $id): void;
}
