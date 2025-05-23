<?php
class EventoController extends BaseController{
    protected EventoRepository $eventoRepository;
    
    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }

    // GET /eventi
    public function index() {
        $utenteId = $_SESSION['user']['id'];
        return Cache::view('eventi_utente_' . $utenteId, 300, function () use ($utenteId) {
            $eventi = $this->eventoRepository->findByUtenteId($utenteId);
            return view('eventi.index', compact('eventi'));          
        });
        
    }

    // GET /eventi/{id}
    public function show($id) { 
        $evento = Cache::remember('eventi_show_' . $id .'_' . $_SESSION['user']['id'], 10, function () use ($id) {      
                return $this->eventoRepository->findById($id);
        });
        if (!$evento) {
                return "Evento non trovato."; 
        }
        if ($evento->utente_id != $_SESSION['user']['id']) {
            return "Non sei autorizzato a vedere questo evento.";   
        }
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
        
       
            $img_path = $this->uploadMedia($_FILES['img_file'], 'upload');        
            $video_path = $this->uploadMedia($_FILES['video_file'], 'upload');
           
            $data = [
                'titolo' => $request['titolo'],
                'descrizione' => $request['descrizione'] ?? null,
                'data_evento' => $request['data_evento'],
                'categoria_id' => (int) $request['categoria_id'],
                'img_path' => $img_path,
                'video_path' => $video_path,
                'utente_id' => $_SESSION['user']['id']
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
        $titolo = $request['titolo'];
        $descrizione = $request['descrizione'];
        $data_evento = $request['data_evento'];


        $data = [
            'titolo' => $titolo,
            'descrizione' => $descrizione,
            'data_evento' => $data_evento,
            'categoria_id' => (int) $request['categoria_id'],
        ];
        $evento = $this->eventoRepository->findById($id);

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

    
}
