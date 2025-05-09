<?php
class Evento {
    public int $id;
    public string $titolo;
    public ?string $descrizione;
    public string $data_evento;
    public ?string $img_path;
    public ?string $video_path;
    public int $utente_id;
    public string $created_at;
    public string $updated_at;

    public function __construct(array $data) {
        $this->id = $data['id'] ?? 0;
        $this->titolo = $data['titolo'] ?? '';
        $this->descrizione = $data['descrizione'] ?? null;
        $this->data_evento = $data['data_evento'] ?? '';
        $this->img_path = $data['img_path'] ?? null;
        $this->video_path = $data['video_path'] ?? null;
        $this->utente_id = $data['utente_id'] ?? 0;
        $this->created_at = $data['created_at'] ?? '';
        $this->updated_at = $data['updated_at'] ?? '';
    }
}
