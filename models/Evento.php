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
    public int $massimo_partecipanti = 100; 
    public ?float $latitudine = null; 
    public ?float $longitudine = null; 
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
    public function numeroIscritti(): int {
        $repo = new EventoIscrizioneRepository();
        return $repo->countByEventoId($this->id);
    }

    public function isUtenteIscritto(int $utenteId): bool {
        $repo = new EventoIscrizioneRepository();
        return $repo->exists($this->id, $utenteId);
    }

}
