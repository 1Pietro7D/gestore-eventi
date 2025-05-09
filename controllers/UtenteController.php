<?php
class UtenteController {
    protected UtenteRepository $utenteRepository;

    public function __construct() {
        $this->utenteRepository = new UtenteRepository();
    }

    // GET /utenti
    public function index() {
        $utenti = $this->utenteRepository->all();
        return view('utenti.index', compact('utenti'));
    }

    // GET /utenti/{id}
    public function show($id) {
        $utente = $this->utenteRepository->findById($id);
        return view('utenti.show', compact('utente'));
    }

    // GET /utenti/create
    public function create() {
        return view('utenti.create');
    }

    // POST /utenti
    public function store($request) {
        $newUser = $this->utenteRepository->create($request);
        redirect('utenti.show', ['id' => $newUser->id]);
    }

    // GET /utenti/{id}/edit
    public function edit($id) {
        $utente = $this->utenteRepository->findById($id);
        return view('utenti.edit', compact('utente'));
    }

    // POST /utenti/{id}/update
    public function update($id, $request) {
        
    $this->utenteRepository->updateById($id, $request);
        redirect('utenti.show', ['id' => $id]);
       
    }

    // POST /utenti/{id}/delete
    public function destroy($id) {
        $this->utenteRepository->deleteById($id);
        redirect("utenti.index");
    }
}
