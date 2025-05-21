<?php

class CategoriaRepository extends BaseRepository
{
    protected string $table = 'categorie';
    protected string $modelClass = Categoria::class;

    public function __construct()
    {
        parent::__construct();
    }
}
