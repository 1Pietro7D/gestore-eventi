<?php
class Database { // definisce la classe Database
    private static ?PDO $conn = null; // connessione PDO singleton, oppure null se non creata

    private static function loadConfig(): array { // carica la configurazione da ENV/.env o default
        // legge prima da $_ENV, poi da getenv(), poi prova a caricare un file .env, infine usa default sicuri
        $vars = [ // inizializza array configurazione
            'DB_HOST'    => $_ENV['DB_HOST']    ?? getenv('DB_HOST')    ?: null, // host DB
            'DB_NAME'    => $_ENV['DB_NAME']    ?? getenv('DB_NAME')    ?: null, // nome DB
            'DB_USER'    => $_ENV['DB_USER']    ?? getenv('DB_USER')    ?: null, // utente DB
            'DB_PASS'    => $_ENV['DB_PASS']    ?? getenv('DB_PASS')    ?: null, // password DB
            'DB_CHARSET' => $_ENV['DB_CHARSET'] ?? getenv('DB_CHARSET') ?: null, // charset
        ]; // fine array vars

        if (in_array(null, $vars, true)) { // se manca qualcosa
            $envPath = __DIR__ . '/../.env'; // path del file .env accanto alla cartella database
            if (is_file($envPath)) { // se esiste il file .env
                $parsed = parse_ini_file($envPath, false, INI_SCANNER_RAW) ?: []; // legge chiavi=valori
                foreach ($parsed as $k => $v) { // per ogni variabile
                    $_ENV[$k] = $v; // popola $_ENV
                    putenv($k . '=' . $v); // setta anche in getenv()
                } // fine foreach
                // ricarica i valori now che .env è stato letto
                $vars = [
                    'DB_HOST'    => $_ENV['DB_HOST']    ?? getenv('DB_HOST')    ?: null,
                    'DB_NAME'    => $_ENV['DB_NAME']    ?? getenv('DB_NAME')    ?: null,
                    'DB_USER'    => $_ENV['DB_USER']    ?? getenv('DB_USER')    ?: null,
                    'DB_PASS'    => $_ENV['DB_PASS']    ?? getenv('DB_PASS')    ?: null,
                    'DB_CHARSET' => $_ENV['DB_CHARSET'] ?? getenv('DB_CHARSET') ?: null,
                ]; // fine ricarica
            } // fine if file .env
        } // fine if in_array

        // imposta default sicuri se ancora mancanti (utile in CLI su Windows)
        $cfg = [ // costruisce configurazione finale
            'host'    => $vars['DB_HOST']    ?: '127.0.0.1', // host di default
            'name'    => $vars['DB_NAME']    ?: 'gestore_eventi', // nome db di default
            'user'    => $vars['DB_USER']    ?: 'root', // utente di default per XAMPP
            'pass'    => $vars['DB_PASS']    ?: '', // password vuota tipica XAMPP
            'charset' => $vars['DB_CHARSET'] ?: 'utf8mb4', // charset predefinito
        ]; // fine array cfg
        return $cfg; // ritorna configurazione
    } // fine loadConfig

    public static function connect(): PDO { // espone la connessione PDO
        if (self::$conn instanceof PDO) { // se già creata
            return self::$conn; // riusa la connessione
        } // fine if
        $cfg = self::loadConfig(); // carica configurazione
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['name'], $cfg['charset']); // DSN completo
        self::$conn = new PDO( // crea istanza PDO
            $dsn, // data source name
            $cfg['user'], // utente
            $cfg['pass'], // password
            [ // opzioni PDO
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // eccezioni sugli errori
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch associativo di default
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $cfg['charset'], // inizializza charset
            ] // fine array opzioni
        ); // fine new PDO
        return self::$conn; // ritorna connessione
    } // fine connect
} // fine classe Database
?>

