<?php

class EventoIscritto extends Model {
    public int $id = 0;
    public int $evento_id = 0;
    public int $utente_id = 0;
    public string $created_at = '';

    public function utente(): Utente {
        $repo = new UtenteRepository();
        return $repo->findById($this->utente_id);
    }

    public function evento(): Evento {
        $repo = new EventoRepository();
        return $repo->findById($this->evento_id);
    }
}
