<?php
class Utente extends Model {
    public $id;
    public $nome;
    public $email;
    public $password;
    public $ruolo;
    public $email_confermata;

    public function toSessionArray(): array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'ruolo' => $this->ruolo
        ];
    }
    
}
