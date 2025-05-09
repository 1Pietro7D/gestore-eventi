<?php
class EventoController {
    protected EventoRepository $eventoRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }

    // GET /eventi
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            echo "Devi essere loggato per visualizzare i tuoi eventi.";
            return;
        }

        $utenteId = $_SESSION['user']['id'];
        $eventi = $this->eventoRepository->findByUtenteId($utenteId);

        return view('eventi.index', compact('eventi'));
    }

    // GET /eventi/{id}
    public function show($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            if (!isset($_SESSION['user']['id'])) {
                throw new Exception("Devi essere loggato per creare un evento.");
            }

            $imgPath = null;
            $videoPath = null;

            // Directory di destinazione upload
            $uploadDir = __DIR__ . '/../public/upload';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Gestione immagine
            if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] === UPLOAD_ERR_OK) {
                $imgTmpPath = $_FILES['img_file']['tmp_name'];
                $imgName = basename($_FILES['img_file']['name']);
                $uniqueImgName = uniqid() . '_' . $imgName;
                $targetImgPath = '/upload/' . $uniqueImgName;
                move_uploaded_file($imgTmpPath, $uploadDir . '/' . $uniqueImgName);
                $imgPath = $targetImgPath;
            }

            // Gestione video
            if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
                $videoTmpPath = $_FILES['video_file']['tmp_name'];
                $videoName = basename($_FILES['video_file']['name']);
                $uniqueVideoName = uniqid() . '_' . $videoName;
                $targetVideoPath = '/upload/' . $uniqueVideoName;
                move_uploaded_file($videoTmpPath, $uploadDir . '/' . $uniqueVideoName);
                $videoPath = $targetVideoPath;
            }

            $data = [
                'titolo' => $request['titolo'],
                'descrizione' => $request['descrizione'] ?? null,
                'data_evento' => $request['data_evento'],
                'img_path' => $imgPath,
                'video_path' => $videoPath,
                'utente_id' => $_SESSION['user']['id']
            ];

            $nuovoEvento = $this->eventoRepository->create($data);
            redirect('eventi.show', ['id' => $nuovoEvento->id]);

        } catch (Exception $e) {
            echo "Errore durante la creazione: " . $e->getMessage();
        }
    }

    // GET /eventi/{id}/edit
    public function edit($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $evento = $this->eventoRepository->findById($id);

        if (!$evento) {
            echo "Evento non trovato.";
            return;
        }

        if ($evento->utente_id != $_SESSION['user']['id']) {
            echo "Non sei autorizzato a modificare questo evento.";
            return;
        }

        $this->eventoRepository->updateById($id, $request);
        redirect('eventi.show', ['id' => $id]);
    }

    // POST /eventi/{id}/delete
    public function destroy($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $evento = $this->eventoRepository->findById($id);

        if (!$evento) {
            echo "Evento non trovato.";
            return;
        }

        if ($evento->utente_id != $_SESSION['user']['id']) {
            echo "Non sei autorizzato a eliminare questo evento.";
            return;
        }

        $this->eventoRepository->deleteById($id);
        redirect('eventi.index');
    }

    // POST /eventi/{id}/update-media
    public function updateMedia($id, $request) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $evento = $this->eventoRepository->findById($id);

        if (!$evento) {
            echo "Evento non trovato.";
            return;
        }

        if ($evento->utente_id != $_SESSION['user']['id']) {
            echo "Non sei autorizzato a modificare i media di questo evento.";
            return;
        }

        $imgPath = null;
        $videoPath = null;

        // Directory di destinazione upload
        $uploadDir = __DIR__ . '/../public/upload';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Gestione nuova immagine
        if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] === UPLOAD_ERR_OK) {
            $imgTmpPath = $_FILES['img_file']['tmp_name'];
            $imgName = basename($_FILES['img_file']['name']);
            $uniqueImgName = uniqid() . '_' . $imgName;
            $targetImgPath = '/upload/' . $uniqueImgName;
            move_uploaded_file($imgTmpPath, $uploadDir . '/' . $uniqueImgName);
            $imgPath = $targetImgPath;
        }

        // Gestione nuovo video
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $videoTmpPath = $_FILES['video_file']['tmp_name'];
            $videoName = basename($_FILES['video_file']['name']);
            $uniqueVideoName = uniqid() . '_' . $videoName;
            $targetVideoPath = '/upload/' . $uniqueVideoName;
            move_uploaded_file($videoTmpPath, $uploadDir . '/' . $uniqueVideoName);
            $videoPath = $targetVideoPath;
        }

        $this->eventoRepository->updateMedia($id, $imgPath, $videoPath);
        redirect('eventi.show', ['id' => $id]);
    }
}
