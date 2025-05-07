<?php
// require_once 'repositories/UtenteRepository.php';

class UtenteController {
    protected UtenteRepository $repo;

    public function __construct() {
        $this->repo = new UtenteRepository();
    }

    // GET /utenti
    public function index() {
        $utenti = $this->repo->all();
        return view('utenti.index', compact('utenti'));
    }

    // GET /utenti/{id}
    public function show($id) {
        $utente = $this->repo->findById($id);
        return view('utenti.show', compact('utente'));
    }

    // GET /utenti/create
    public function create() {
        return view('utenti.create');
    }

    // POST /utenti
    public function store($request) {
        $this->repo->create($request);
        header("Location: /utenti");
        exit;
    }

    // GET /utenti/{id}/edit
    public function edit($id) {
        $utente = $this->repo->findById($id);
        return view('utenti.edit', compact('utente'));
    }

    // POST /utenti/{id}/update
    public function update($id, $request) {
        $this->repo->updateById($id, $request);
        header("Location: /utenti/show/$id");
        exit;
    }

    // POST /utenti/{id}/delete
    public function destroy($id) {
        $this->repo->deleteById($id);
        header("Location: /utenti");
        exit;
    }
}
