<?php

class HomeController {
     protected EventoRepository $eventoRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }
    // public function index() {
    //     $eventi = Cache::remember('eventi_home' , 0, function () {
    //         return $this->eventoRepository->all();         
    //     });
        
    //     return view('home', compact('eventi'));
    // }
    public function index() {
        // Cache::clearAll();
        $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        $cacheKey = "eventi_home_pagina_$pagina";

        $paginazione = Cache::remember($cacheKey, 0, function () use ($pagina) {
            $_GET['pagina'] = $pagina;
            return $this->eventoRepository->paginate(10);
        });

        return view('home', compact('paginazione'));
    }
}

