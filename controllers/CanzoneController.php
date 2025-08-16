<?php
class CanzoneController extends BaseController {
    protected CanzoneRepository $repo;

    public function __construct() {
        $this->repo = new CanzoneRepository();
    }

    public function index() {
        $canzoni = $this->repo->all();
        return view('canzoni.index', compact('canzoni'));
    }

    public function admin() {
        $canzoni = $this->repo->all();
        return view('canzoni.admin', compact('canzoni'));
    }

    public function create() {
        return view('canzoni.create');
    }

    public function store($request) {
        $request = $this->sanitizeRequest($request);
        $errors = $this->validatore($request);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return redirect('canzoni.create');
        }
        $data = [
            'titolo' => trim($request['titolo']),
            'artista' => trim($request['artista']),
            'youtube_url' => trim($request['youtube_url']),
            'utente_id' => $_SESSION['user']['id'],
        ];
        $this->repo->create($data);
        return redirect('canzoni.admin');
    }

    public function edit($id) {
        $canzone = $this->repo->findById((int)$id);
        if (!$canzone) {
            $_SESSION['errors'][] = 'Canzone non trovata.';
            return redirect('canzoni.admin');
        }
        return view('canzoni.edit', compact('canzone'));
    }

    public function update($id, $request) {
        $request = $this->sanitizeRequest($request);
        $errors = $this->validatore($request, (int)$id);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return redirect('canzoni.edit', ['id' => $id]);
        }
        $data = [
            'titolo' => trim($request['titolo']),
            'artista' => trim($request['artista']),
            'youtube_url' => trim($request['youtube_url']),
        ];
        $this->repo->updateById((int)$id, $data);
        return redirect('canzoni.admin');
    }

    public function destroy($id) {
        $this->repo->deleteById((int)$id);
        return redirect('canzoni.admin');
    }

    private function validatore(array $data, ?int $id = null): array {
        $errors = [];
        if (empty($data['titolo'])) $errors[] = 'Il titolo è obbligatorio';
        if (empty($data['artista'])) $errors[] = 'L\'artista è obbligatorio';
        if (empty($data['youtube_url'])) $errors[] = 'L\'URL YouTube è obbligatorio';
        return $errors;
    }
}


