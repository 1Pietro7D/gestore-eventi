<?php

class HomeController {
    protected EventoRepository $eventoRepository;
    protected CategoriaRepository $categoriaRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
        $this->categoriaRepository = new CategoriaRepository();
    }
 
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

