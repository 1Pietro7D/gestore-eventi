<?php
require_once 'repositories/UtenteRepository.php';

class CiaoController {
    public function index() {
        $repo = new UtenteRepository();
        $dati = $repo->all();

        return view('ciao', compact('dati'));
    }
}
