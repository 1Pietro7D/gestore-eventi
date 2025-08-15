<?php
function serveStaticResource(string $resourcesDir = __DIR__ . '/../resources'): void
{
    // Ottieni path richiesto dal PATH_INFO o REQUEST_URI
    $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = '/resources/';

    if (!str_starts_with($uriPath, $basePath)) {
        return; // non è una richiesta a /resources
    }

    // Estrae il percorso del file dopo /resources/
    $requestedFile = ltrim(substr($uriPath, strlen($basePath)), '/');

    // Percorso reale sul server
    $resourcePath = realpath($resourcesDir . '/' . $requestedFile);

    // Controllo che sia dentro /resources
    $isInsideResources = $resourcePath && str_starts_with($resourcePath, realpath($resourcesDir));

    if (!$isInsideResources || !file_exists($resourcePath)) {
        http_response_code(404);
        echo 'File not found';
        exit;
    }

    // 🔐 Controllo Referer e token CSRF
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $host = $_SERVER['HTTP_HOST'];
    //$token = $_GET['token'] ?? '';

    //$csrfValid = method_exists('Csrf', 'check') ? Csrf::check($token) : false;

    if (!str_contains($referer, $host) /*&& !$csrfValid*/) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }

    // ✅ Serve il file con Content-Type corretto
    $ext = pathinfo($resourcePath, PATHINFO_EXTENSION);
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'woff2' => 'font/woff2',
        'woff' => 'font/woff',
    ];

    header('Content-Type: ' . ($mimeTypes[$ext] ?? 'application/octet-stream'));
    header('Cache-Control: max-age=86400');
    readfile($resourcePath);
    exit;
}

/**
 * Serve un file dalla cartella /public/upload/ come risorsa statica,
 * con controlli di sicurezza per evitare accessi non autorizzati.
 *
 * @param string $uploadsDir Percorso assoluto alla cartella "upload"
 */
function serveUploadResource(string $uploadsDir = __DIR__ . '/../public/upload'): void
{
    // Estrae SOLO il path dall'URL (senza query string, es. "?v=123")
    $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Prefisso che identifica le richieste agli upload
    $basePath = '/upload/';

    // Se l'URL non inizia con /upload/, non gestiamo la richiesta
    if (!str_starts_with($uriPath, $basePath)) {
        return;
    }

    // Decodifica URL (%20 → spazio, ecc.) e rimuove eventuali slash iniziali
    $requestedFile = urldecode(ltrim(substr($uriPath, strlen($basePath)), '/'));

    // Prevenzione attacchi "null byte" o path injection
    if (str_contains($requestedFile, "\0") || str_contains($requestedFile, '..')) {
        http_response_code(400); // Bad Request
        echo 'Invalid file path';
        exit;
    }

    // Normalizza i percorsi reali (permette confronti sicuri)
    $uploadsRealPath = str_replace('\\', '/', realpath($uploadsDir)); // cartella upload
    $filePath        = str_replace('\\', '/', realpath($uploadsDir . '/' . $requestedFile)); // file richiesto

    // Verifica: il file richiesto deve essere DENTRO la cartella upload
    $isInsideUploads = $filePath && str_starts_with($filePath, $uploadsRealPath);

    // Se non esiste o non è nella cartella consentita → 404
    if (!$isInsideUploads || !file_exists($filePath)) {
        http_response_code(404);
        echo 'File not found';
        exit;
    }

    // Determina l'estensione (in minuscolo)
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    // Mappa estensione → MIME type
    $mimeTypes = [
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'svg'  => 'image/svg+xml',
        // Se vuoi, qui puoi aggiungere PDF o altri formati
    ];

    // Imposta Content-Type (o fallback generico)
    header('Content-Type: ' . ($mimeTypes[$ext] ?? 'application/octet-stream'));

    // Attiva cache lato browser (24 ore)
    header('Cache-Control: max-age=86400');

    // Invia il contenuto del file al client
    readfile($filePath);
    exit;
}




function serveUploadResourceDebug(string $uploadsDir = __DIR__ . '/../public/upload'): void
{
    $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = '/upload/';

    if (!str_starts_with($uriPath, $basePath)) {
        return; // non è una richiesta a /upload
    }

    // Decodifica eventuali %20, ecc.
    $requestedFile = urldecode(ltrim(substr($uriPath, strlen($basePath)), '/'));

    // Percorsi calcolati
    $uploadsRealPath = realpath($uploadsDir);
    $filePath = realpath($uploadsDir . '/' . $requestedFile);

    // --- DEBUG ---
    header('Content-Type: text/plain; charset=utf-8');
    echo "DEBUG SERVE UPLOAD RESOURCE\n";
    echo "URI Path: {$uriPath}\n";
    echo "Requested File: {$requestedFile}\n";
    echo "UploadsDir: {$uploadsDir}\n";
    echo "Uploads Real Path: {$uploadsRealPath}\n";
    echo "File Path: {$filePath}\n";
    echo "File Exists? " . (file_exists($filePath) ? 'YES' : 'NO') . "\n";
    exit;
}
