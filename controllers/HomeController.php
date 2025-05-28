<?php

class HomeController {
    protected EventoRepository $eventoRepository;
    protected CategoriaRepository $categoriaRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
        $this->categoriaRepository = new CategoriaRepository();
    }
    // public function index() {
    //     $eventi = Cache::remember('eventi_home' , 0, function () {
    //         return $this->eventoRepository->all();         
    //     });
        
    //     return view('home', compact('eventi'));
    // }

    // public function index() {
    //     // Cache::clearAll();
    //     $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
    //     $cacheKey = "eventi_home_pagina_$pagina";

    //     $paginazione = Cache::remember($cacheKey, 0, function () use ($pagina) {
    //         $_GET['pagina'] = $pagina;
    //         return $this->eventoRepository->paginate(10);
    //     });
    //     // return $this->eventoRepository->findByCategoria(2)->paginate(10);
    //     // echo '<pre>';
    //     // print_r($paginazione);
    //     // echo '</pre>';
    //     // exit;
    //     return view('home', compact('paginazione'));
    // }
    public function index() {
        $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        $categoriaId = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;

        $cacheKey = "eventi_home_categoria_{$categoriaId}_pagina_{$pagina}";

        $paginazione = Cache::remember($cacheKey, 0, function () use ($pagina, $categoriaId) {
            $_GET['pagina'] = $pagina;
            if ($categoriaId) {
                return $this->eventoRepository->findByCategoria($categoriaId)->paginate(10);
            }
            return $this->eventoRepository->paginate(10);
        });

        $categorie = $this->categoriaRepository->all(); // tutte le categorie
    
        return view('home', compact('paginazione', 'categorie', 'categoriaId'));
    }
}

