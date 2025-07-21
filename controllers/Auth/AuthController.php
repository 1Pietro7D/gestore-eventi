<?php
class AuthController extends BaseController{
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
            if (!$user->email_confermata) {
                return redirect('verifica-otp');               
            }
            $_SESSION['user'] = $user->toSessionArray();
            redirect('home');
        } else {
            $_SESSION['errors']['credenziali'] = "non valide";
            redirect('login');
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
        echo "Il tuo account � stato cancellato.";
    }

    public function showRegister() {
        return view('register');
    }

    public function register($request) {
        $request = $this->sanitizeRequest($request);
        $errors = $this->validatore($request);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return redirect('register');            
        }

        // Crea l'utente (email_confermata rimane 0 di default)
        $user = $this->utenteRepository->create($request);

        // Genera OTP
        $otp = random_int(100000, 999999);

        // Salva OTP
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO otp_verifiche (user_id, codice, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user->id, $otp]);

        // Invia OTP via email
        $html = "<p>Il tuo codice OTP �: <strong>$otp</strong></p>";
        require_once __DIR__.('/../../utils/Gmail.php');
        sendOtpWithGmail($html, $user->email);

        // Memorizza user_id in sessione per conferma OTP
        $_SESSION['otp_user_id'] = $user->id;

        redirect('verifica-otp');
    }

    public function showVerificaOtp() {
        return view('verifica-otp');
    }
    public function verificaOtp($request) {
        if (!isset($_SESSION['otp_user_id'])) {
            echo "Sessione scaduta. Registrati di nuovo.";
            return;
        }

        $userId = $_SESSION['otp_user_id'];
        $otpInserito = $request['otp'];

        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM otp_verifiche WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $record = $stmt->fetch();

        if ($record && $record['codice'] === $otpInserito && strtotime($record['created_at']) > time() - 300) {
            // Conferma email
            $update = $db->prepare("UPDATE utenti SET email_confermata = 1 WHERE id = ?");
            $update->execute([$userId]);

            // Logga l'utente e rimuovi OTP
            $utente = $this->utenteRepository->findById($userId);
            $_SESSION['user'] = $utente->toSessionArray();

            unset($_SESSION['otp_user_id']);
            redirect('home');
        } else {
            $_SESSION['errors']['OTP'] = "Codice OTP non valido o scaduto.";
            // echo "Codice OTP non valido o scaduto.";
            redirect('verifica-otp');
        }
    }

    
    private function validatore(array $request): array {
        $errors = [];

        // Validazione email
        if (empty($request['email']) || !filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email non valida.';
        } else {
            $existing = $this->utenteRepository->findByEmail($request['email']);
            if ($existing) {
                $errors['email'] = 'Email gi� registrata.';
            }
        }

        // Validazione password (es. minimo 8 caratteri, niente caratteri sospetti)
        if (empty($request['password']) || strlen($request['password']) < 8) {
            $errors['password'] = 'La password deve contenere almeno 8 caratteri.';
        } elseif (preg_match('/[<>{};]/', $request['password'])) {
            $errors['password'] = 'La password contiene caratteri non consentiti.';
        }

        // Validazione ruolo
        $ruoliValidi = ['user', 'admin'];
        if (empty($request['ruolo']) || !in_array($request['ruolo'], $ruoliValidi)) {
            $errors['ruolo'] = 'Ruolo non valido. Deve essere user o admin.';
        }

        return $errors;
    }

}
