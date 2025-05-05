<?php
require_once 'core/Database.php';

class Utente {
    public function login($email, $password) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM utenti WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public function register($nome, $email, $password, $ruolo) {
        $db = Database::connect();
        $check = $db->prepare("SELECT * FROM utenti WHERE email = :email");
        $check->execute([':email' => $email]);
        if ($check->fetch()) return null;

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO utenti (nome, email, password, ruolo) VALUES (:nome, :email, :password, :ruolo)");
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':password' => $hash,
            ':ruolo' => $ruolo
        ]);
        return $this->login($email, $password);
    }

    public function getUtenti() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM utenti");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM utenti WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    
}
