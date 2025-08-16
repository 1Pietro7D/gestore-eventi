<?php
class Canzone extends Model {
    public int $id = 0;
    public int $utente_id = 0; // riferimento all'utente proprietario
    public string $titolo = '';
    public string $artista = '';
    public string $youtube_url = '';
    public string $created_at = '';
    public string $updated_at = '';

    public function getYouTubeId(): ?string {
        $url = trim($this->youtube_url);
        if ($url === '') return null;
        $patterns = [
            '/[?&]v=([\w-]{11})/i',
            '#youtu\.be/([\w-]{11})#i',
            '#/embed/([\w-]{11})#i',
        ];
        foreach ($patterns as $p) {
            if (preg_match($p, $url, $m)) {
                return $m[1];
            }
        }
        return null;
    }
}