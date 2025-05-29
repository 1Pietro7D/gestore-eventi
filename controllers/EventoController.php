<?php
class EventoController extends BaseController{
    protected EventoRepository $eventoRepository;
    
    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }

    // GET /eventi
    public function index() {
        $utenteId = $_SESSION['user']['id'];
        return Cache::view('eventi_utente_' . $utenteId, 0, function () use ($utenteId) {
            $eventi = $this->eventoRepository->findByUtenteId($utenteId);
            return view('eventi.index', compact('eventi'));          
        });
        
    }

    // GET /eventi/{id}
    public function show($id) { 
        $userId = $_SESSION['user']['id'] ?? 'guest';  // fallback se non loggato
        $cacheKey = "eventi_show_{$id}_{$userId}";

        $evento = Cache::remember($cacheKey, 10, function () use ($id) {
            return $this->eventoRepository->findById($id);
        });
        if (!$evento) {
                return "Evento non trovato."; 
        }
        // if ($evento->utente_id != $_SESSION['user']['id']) {
        //     return "Non sei autorizzato a vedere questo evento.";   
        // }
        return view('eventi.show', compact('evento'));
    }

    // GET /eventi/create
    public function create() {
        $categoriaRepo = new CategoriaRepository();
        $categorie = $categoriaRepo->all();
        return view('eventi.create', compact('categorie'));
    }

    // POST /eventi
    public function store($request) {
        $errors = $this->validatore($request);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('eventi.create');
            return;
        }
       
        $img_path = $this->uploadMedia($_FILES['img_file'], 'upload');        
        $video_path = $this->uploadMedia($_FILES['video_file'], 'upload');
        
        $data = [
            'titolo' => $request['titolo'],
            'descrizione' => $request['descrizione'] ?? null,
            'data_evento' => $request['data_evento'],
            'categoria_id' => (int) $request['categoria_id'],
            'img_path' => $img_path,
            'video_path' => $video_path,
            'utente_id' => $_SESSION['user']['id'],
            'latitudine'=> !empty($request['latitudine']) ? $request['latitudine'] : null,
            'longitudine'=> !empty($request['longitudine']) ? $request['longitudine'] : null,
            'massimo_partecipanti' => isset($request['massimo_partecipanti']) ? (int) $request['massimo_partecipanti'] : 100,
            'prezzo' => isset($request['prezzo']) ? (float) $request['prezzo'] : 0.0,

        ];
        $nuovoEvento = $this->eventoRepository->create($data);
        Cache::clear('eventi_utente_' . $_SESSION['user']['id']);
        Cache::clear('eventi_home');
        redirect('eventi.show', ['id' => $nuovoEvento->id]);

    }

    // GET /eventi/{id}/edit
    public function edit($id) {
        $evento = $this->eventoRepository->findById($id);

        if (!$evento) {
            echo "Evento non trovato.";
            return;
        }

        if ($evento->utente_id != $_SESSION['user']['id']) {
            echo "Non sei autorizzato a modificare questo evento.";
            return;
        }
        
        $categoriaRepo = new CategoriaRepository();
        $categorie = $categoriaRepo->all();

        return view('eventi.edit', compact('evento', 'categorie'));
    }

    // POST /eventi/{id}/update
    public function update($id, $request) {
        $prezzo = isset($request['prezzo']) ? (float) $request['prezzo'] : 0.0;
        $titolo = $request['titolo'];
        $descrizione = $request['descrizione'];
        $data_evento = $request['data_evento'];
        $categoriaId = (int) $request['categoria_id'];
        $latitudine= !empty($request['latitudine']) ? $request['latitudine'] : null;
        $longitudine= !empty($request['longitudine']) ? $request['longitudine'] : null;
        $massimo_partecipanti = isset($request['massimo_partecipanti']) ? (int) $request['massimo_partecipanti'] : 100;

        $errors = $this->validatore($request);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('eventi.edit',['id' => $id]);
            return;
        }     
        

        $data = [
            'titolo' => $titolo,
            'descrizione' => $descrizione,
            'data_evento' => $data_evento,
            'categoria_id' => $categoriaId,
            'latitudine' => $latitudine,
            'longitudine' => $longitudine,
            'massimo_partecipanti' => $massimo_partecipanti,
            'prezzo' => $prezzo,
        ];
        $evento = $this->eventoRepository->findById($id);
        if ($evento->utente_id != $_SESSION['user']['id']) {
            echo "Non sei autorizzato a modificare questo evento.";
            return;
        }
        $img_path = $this->uploadMedia($_FILES['img_file'], 'upload', $evento->img_path);
        $data['img_path'] = $img_path;
        
        $video_path = $this->uploadMedia($_FILES['video_file'], 'upload', $evento->video_path);
        $data['video_path'] = $video_path;
      
        $this->eventoRepository->updateById($id, $data);
        Cache::clear('eventi_show_' . $id .'_' . $_SESSION['user']['id']);
        Cache::clear('eventi_utente_' . $evento->utente_id);
        Cache::clear('eventi_home');
        redirect('eventi.show', ['id' => $id]);
    }


    // POST /eventi/{id}/delete
    public function destroy($id) {
        $evento = $this->eventoRepository->findById($id);
        if (!$evento) {
            echo "Evento non trovato.";
            return;
        }
        $this->deleteMedia($evento->img_path);
        $this->deleteMedia($evento->video_path);  

        if ($evento->utente_id != $_SESSION['user']['id']) {
            echo "Non sei autorizzato a eliminare questo evento.";
            return;
        }

        $this->eventoRepository->deleteById($id);
      
        Cache::clear('eventi_show_' . $id .'_' . $_SESSION['user']['id']);
        Cache::clear('eventi_utente_' . $evento->utente_id);
        Cache::clear('eventi_home');
        redirect('eventi.index');
    }

    public function iscriviti($id) {
        if (!isset($_SESSION['user'])) {
            redirect('login');
            return;
        }
    
        $evento = $this->eventoRepository->findById($id);
        if (!$evento) {
            echo "Evento non trovato.";
            return;
        }
    
        $repo = new EventoIscrizioneRepository();
    
        if ($repo->exists($evento->id, $_SESSION['user']['id'])) {
            $_SESSION['errors'][] = "Sei già iscritto a questo evento.";
            redirect('eventi.show', ['id' => $evento->id]);
            return;
        }
    
        if ($repo->countByEventoId($evento->id) >= $evento->massimo_partecipanti) {
            $_SESSION['errors'][] = "Posti esauriti per questo evento.";
            redirect('eventi.show', ['id' => $evento->id]);
            return;
        }
    
        $repo->create([
            'evento_id' => $evento->id,
            'utente_id' => $_SESSION['user']['id']
        ]);
    
        $_SESSION['success'] = "Iscrizione avvenuta con successo!";
        Cache::clear('eventi_home');
        Cache::clear('eventi_show_' . $evento->id . '_' . $_SESSION['user']['id']);
        redirect('eventi.show', ['id' => $evento->id]);
    }

    public function annullaIscrizione($id) {
        if (!isset($_SESSION['user'])) {
            redirect('login');
            return;
        }

        $evento = $this->eventoRepository->findById($id);
        if (!$evento) {
            echo "Evento non trovato.";
            return;
        }

        $repo = new EventoIscrizioneRepository();

        if (!$repo->exists($evento->id, $_SESSION['user']['id'])) {
            $_SESSION['errors'][] = "Non risulti iscritto a questo evento.";
            redirect('eventi.show', ['id' => $evento->id]);
            return;
        }

        $repo->deleteByEventoAndUtente($evento->id, $_SESSION['user']['id']);

        $_SESSION['success'] = "Iscrizione annullata con successo.";
        Cache::clear('eventi_home');
        Cache::clear('eventi_show_' . $evento->id . '_' . $_SESSION['user']['id']);
        redirect('eventi.show', ['id' => $evento->id]);
    }

    private function validatore(array $request): array {
        $errors = [];
        
        if (empty($request['titolo']) || strlen(trim($request['titolo'])) < 3) {
            $errors[] = "Il titolo è obbligatorio e deve contenere almeno 3 caratteri.";
        }
    
        if (!empty($request['descrizione']) && strlen($request['descrizione']) > 1000) {
            $errors[] = "La descrizione non può superare i 1000 caratteri.";
        }
    
        if (empty($request['data_evento']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $request['data_evento'])) {
            $errors[] = "Data evento non valida o mancante.";
        }
    
        $categoriaId = $request['categoria_id'] ?? null;
        if (empty($categoriaId) || !is_numeric($categoriaId)) {
            $errors[] = "Categoria obbligatoria.";
        } else {
            $categoriaRepo = new CategoriaRepository();
            if (!$categoriaRepo->findById((int) $categoriaId)) {
                $errors[] = "Categoria selezionata non valida.";
            }
        }
        // Validazione latitudine
        if (!isset($request['latitudine']) || !is_numeric($request['latitudine'])) {
            $errors[] = "Latitudine mancante o non valida.";
        } elseif ($request['latitudine'] < -90 || $request['latitudine'] > 90) {
            $errors[] = "La latitudine deve essere compresa tra -90 e 90.";
        }
    
        // Validazione longitudine
        if (!isset($request['longitudine']) || !is_numeric($request['longitudine'])) {
            $errors[] = "Longitudine mancante o non valida.";
        } elseif ($request['longitudine'] < -180 || $request['longitudine'] > 180) {
            $errors[] = "La longitudine deve essere compresa tra -180 e 180.";
        }
        $prezzoMassimo = 1000;
        if (!isset($request['prezzo']) || !is_numeric($request['prezzo']) || $request['prezzo'] < 0) {
            $errors[] = "Il prezzo deve essere un numero positivo.";
        } elseif ($request['prezzo'] > $prezzoMassimo) {
            $errors[] = "Il prezzo massimo consentito è {$prezzoMassimo} €.";
        }
        // Validazione numero massimo di partecipanti
        if (!isset($request['massimo_partecipanti']) || !is_numeric($request['massimo_partecipanti']) || $request['massimo_partecipanti'] <= 0) {
            $errors[] = "Il numero massimo di partecipanti deve essere un intero positivo.";
        }
        return $errors;
    }

}
