<?php
class AuthController {
    protected $utenteRepository;

    public function __construct() {
        $this->utenteRepository = new UtenteRepository();
    }

    public function showLogin() {
        return view('login');
    }

    public function login($request) {
        $user = $this->utenteRepository->findByEmail($request['email']);

        if ($user && password_verify($request['password'], $user->password)) {
            $_SESSION['user'] = $user->toSessionArray();
            redirect('home');
        } else {
            echo "Credenziali non valide";
        }
    }

    public function logout() {
        session_destroy();
        redirect('login');
    }

    public function deleteUser() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo "Non autorizzato.";
            return;
        }

        $this->utenteRepository->deleteById($_SESSION['user']['id']);
        unset($_SESSION['user']);
        echo "Il tuo account è stato cancellato.";
    }

    public function showRegister() {
        return view('register');
    }

    public function register($request) {
        $existing = $this->utenteRepository->findByEmail($request['email']);

        if ($existing) {
            echo "Utente è già registrato";
            // return;
            exit; 
        }

        $user = $this->utenteRepository->create($request);
        $_SESSION['user'] = $user->toSessionArray();

        redirect('home');
    }
}
