<?php

class ApiHomeController extends BaseController{
    protected EventoRepository $eventoRepository;
    protected CategoriaRepository $categoriaRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
        $this->categoriaRepository = new CategoriaRepository();
    }

    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        $categoriaId = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;

        $cacheKey = "eventi_home_categoria_{$categoriaId}_pagina_{$pagina}";

        $paginazione = Cache::remember($cacheKey, 0, function () use ($pagina, $categoriaId) {
            $_GET['pagina'] = $pagina;
            if ($categoriaId) {
                return $this->eventoRepository->findByCategoria($categoriaId)->paginate(10);
            }
            return $this->eventoRepository->paginate(2);
        });

        $categorie = $this->categoriaRepository->all();

        echo json_encode([
            'eventi' => $paginazione['data'],
            'pagina_corrente' => $paginazione['current_page'],
            'totale_pagine' => $paginazione['last_page'],
            'categorie' => $categorie,
            'categoria_selezionata' => $categoriaId
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
