<?php

class HomeController {
     protected EventoRepository $eventoRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }
    public function index() {
        $eventi = $this->eventoRepository->all();
        return view('home', compact('eventi'));
    }
}

