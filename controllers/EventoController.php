<?php
class EventoController extends BaseController{
    protected EventoRepository $eventoRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }

    // GET /eventi
    public function index() {
        $utenteId = $_SESSION['user']['id'];
        $eventi = $this->eventoRepository->findByUtenteId($utenteId);

        return view('eventi.index', compact('eventi'));
    }

    // GET /eventi/{id}
    public function show($id) {
        $evento = $this->eventoRepository->findById($id);

        if (!$evento) {
            echo "Evento non trovato.";
            return;
        }

        if ($evento->utente_id != $_SESSION['user']['id']) {
            echo "Non sei autorizzato a vedere questo evento.";
            return;
        }

        return view('eventi.show', compact('evento'));
    }

    // GET /eventi/create
    public function create() {
        return view('eventi.create');
    }

    // POST /eventi
    public function store($request) {
        
       
            $img_path = $this->uploadMedia($_FILES['img_file'], 'upload');        
            $video_path = $this->uploadMedia($_FILES['video_file'], 'upload');
           
            $data = [
                'titolo' => $request['titolo'],
                'descrizione' => $request['descrizione'] ?? null,
                'data_evento' => $request['data_evento'],
                'img_path' => $img_path,
                'video_path' => $video_path,
                'utente_id' => $_SESSION['user']['id']
            ];

            $nuovoEvento = $this->eventoRepository->create($data);
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

        return view('eventi.edit', compact('evento'));
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
        ];
        $evento = $this->eventoRepository->findById($id);

        $img_path = $this->uploadMedia($_FILES['img_file'], 'upload', $evento->img_path);
        $data['img_path'] = $img_path;
        
        $video_path = $this->uploadMedia($_FILES['video_file'], 'upload', $evento->video_path);
        $data['video_path'] = $video_path;
      
        $this->eventoRepository->updateById($id, $data);

        header('Location: /eventi/show/' . $id);
        exit;
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
        redirect('eventi.index');
    }

    
}
