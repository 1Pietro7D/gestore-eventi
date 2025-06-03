<?php
class CategorieController extends BaseController {
    protected CategoriaRepository $categoriaRepository;

    public function __construct() {
        $this->categoriaRepository = new CategoriaRepository();
    }

    // GET /categorie
    public function index() {
        $categorie = $this->categoriaRepository->all();
        return view('categorie.index', compact('categorie'));
    }

    // GET /categorie/create
    public function create() {
        return view('categorie.create');
    }

    // POST /categorie
    public function store($request) {
        $nome = trim($request['nome'] ?? '');

        if ($nome === '') {
            echo "Il nome della categoria è obbligatorio.";
            return;
        }

        $this->categoriaRepository->create(['nome' => $nome]);
        redirect('categorie.index');
    }

    // GET /categorie/{id}/edit
    public function edit($id) {
        $categoria = $this->categoriaRepository->findById($id);

        if (!$categoria) {
            echo "Categoria non trovata.";
            return;
        }

        return view('categorie.edit', compact('categoria'));
    }

    // POST /categorie/{id}/update
    public function update($id, $request) {
        $nome = trim($request['nome'] ?? '');

        if ($nome === '') {
            echo "Il nome della categoria è obbligatorio.";
            return;
        }

        $categoria = $this->categoriaRepository->findById($id);

        if (!$categoria) {
            echo "Categoria non trovata.";
            return;
        }

        $this->categoriaRepository->updateById($id, ['nome' => $nome]);
        redirect('categorie.index');
    }

    // POST /categorie/{id}/delete
    public function destroy($id) {
        $categoria = $this->categoriaRepository->findById($id);

        if (!$categoria) {
            echo "Categoria non trovata.";
            return;
        }

        $this->categoriaRepository->deleteById($id);
        redirect('categorie.index');
    }
}
