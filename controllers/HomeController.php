<?php

class HomeController {
     protected EventoRepository $eventoRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }
    public function index() {
        $eventi = Cache::remember('eventi_home' , 0, function () {
            return $this->eventoRepository->all();         
        });
        
        return view('home', compact('eventi'));
    }
}

