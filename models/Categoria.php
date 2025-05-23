<?php 

class Categoria extends Model {
    public int $id = 0;
    public string $nome = '';
    public string $created_at= '';
    public string $updated_at= '';

    // public function __construct(array $data)
    // {
    //     $this->id = (int) $data['id'];
    //     $this->nome = $data['nome'];
    //     $this->created_at = $data['created_at'];
    //     $this->updated_at = $data['updated_at'];
    // }
}
