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
         $errors = $this->validatore($request);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return redirect('utenti.create');            
        }
        $newUser = $this->utenteRepository->create($request);
        redirect('utenti.show', ['id' => $newUser->id]);
    }

    // GET /utenti/edit/{id}
    public function edit($id) {
        $utente = $this->utenteRepository->findById($id);
        return view('utenti.edit', compact('utente'));
    }

    // POST /utenti/update/{id}
    public function update($id, $request) {
        $errors = $this->validatore($request, $id);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return redirect('utenti.edit', ['id' => $id]);            
        }
        // Rimuove la password se lasciata vuota, così non viene aggiornata
        if (empty($request['password'])) {
            unset($request['password']);
        }
        $this->utenteRepository->updateById($id, $request);
        redirect('utenti.show', ['id' => $id]);
       
    }

    // POST /utenti/delete/{id}
    public function destroy($id) {
        $utente = $this->utenteRepository->findById($id);
        if ($utente->ruolo == 'admin') {
            Cache::clear('eventi_home');
        }
        $this->utenteRepository->deleteById($id);
       
        redirect("utenti.index");
    }
    private function validatore(array $request, int $id = 0): array {
        $errors = [];

        // Validazione email  
        if (empty($request['email']) || !filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email non valida.';
        }
        
        if ($id ==0 || $this->utenteRepository->findById($id)->email != $request['email']) {
           $existing = $this->utenteRepository->findByEmail($request['email']);
            if ($existing) {
                $errors['email'] = 'Email già registrata.';
            }
        }

        // // Validazione password (es. minimo 8 caratteri, niente caratteri sospetti)
        if(!empty($request['password'])){
            if ( strlen($request['password']) < 8) {
                $errors['password'] = 'La password deve contenere almeno 8 caratteri.';
            } elseif (preg_match('/[<>{};]/', $request['password'])) {
                $errors['password'] = 'La password contiene caratteri non consentiti.';
            }
        }
        // Validazione ruolo
        $ruoliValidi = ['user', 'admin'];
        if (empty($request['ruolo']) || !in_array($request['ruolo'], $ruoliValidi)) {
            $errors['ruolo'] = 'Ruolo non valido. Deve essere user o admin.';
        }

        return $errors;
    }
}
