<?php
class Evento extends Model {
    public int $id = 0;
    public string $titolo = '';
    public ?string $descrizione = null;
    public string $data_evento = '';
    public ?string $img_path = null;
    public ?string $video_path = null;
    public int $utente_id = 0;
    public int $categoria_id = 0; // non null perché obbligatorio
    public string $created_at = '';
    public string $updated_at = '';

    public function categoria(): Categoria {
        $repo = new CategoriaRepository();
        $categoria = $repo->findById($this->categoria_id);
        if (!$categoria) {
            throw new RuntimeException("Categoria non trovata per categoria_id = {$this->categoria_id}");
        }
        return $categoria;
    }
}
