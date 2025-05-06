<?php

 //require_once 'repositories/UtenteRepository.php';

class AuthController {
    protected $repo;

    public function __construct() {
        $this->repo = new UtenteRepository();
    }

    public function showLogin($request) {
        return view('login');
    }

    public function login($request) {
        $user = $this->repo->findByEmail($request['email']);

        if ($user && password_verify($request['password'], $user->password)) {
            $_SESSION['user'] = $user->toSessionArray();
            header("Location: /");
            exit;
        } else {
            echo "Credenziali non valide";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
        exit;
    }

    public function deleteUser() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo "Non autorizzato.";
            return;
        }

        $this->repo->deleteById($_SESSION['user']['id']);
        unset($_SESSION['user']);
        echo "Il tuo account � stato cancellato.";
    }

    public function showRegister() {
        return view('register');
    }

    public function register($request) {
        $existing = $this->repo->findByEmail($request['email']);

        if ($existing) {
            echo "Utente gi� registrato";
            return;
        }

        $user = $this->repo->create($request);
        $_SESSION['user'] = $user->toSessionArray();

        header("Location: /");
        exit;
    }
}
