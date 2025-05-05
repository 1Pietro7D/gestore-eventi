<?php
require_once 'models/Utente.php';

class AuthController {
    public function showLogin($request) {
        require 'views/login.php';
    }

    public function login($request) {
        $utente = new Utente();
        $user = $utente->login($request['email'], $request['password']);
        if ($user) {
            $_SESSION['user'] = $user;
            header("Location: /mini3/");
            exit;
        } else {
            echo "Credenziali non valide";
        }
    }

    public function logout($request) {
        session_destroy();
        header("Location: /mini3/login");
        exit;
    }

    public function deleteUser($request) {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo "Non autorizzato.";
            return;
        }
    
        $utente = new Utente();
        $utente->delete($_SESSION['user']['id']);
    
        unset($_SESSION['user']);
        echo "Il tuo account Ë stato cancellato.";
    }
    

    public function showRegister($request) {
        require 'views/register.php';
    }

    public function register($request) {
        $utente = new Utente();
        $user = $utente->register($request['nome'], $request['email'], $request['password'], $request['ruolo'] ?? 'user');
        if ($user) {
            $_SESSION['user'] = $user;
            header("Location: /mini3/");
            exit;
        } else {
            echo "Utente gi√† registrato";
        }
    }
}
