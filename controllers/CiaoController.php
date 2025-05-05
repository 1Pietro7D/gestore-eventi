<?php
require_once 'models/Utente.php';

class CiaoController {
    public function index($request) {
        $utente = new Utente();
        $dati = $utente->getUtenti();
        require 'views/ciao.php';
    }
}
