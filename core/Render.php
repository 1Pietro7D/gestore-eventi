<?php
function view(string $path, array $data = []): string {
    $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $path) . '.php';
    if (!file_exists($viewFile)) {
        http_response_code(500);
        return "Errore: view <code>$path</code> non trovata.";
    }
    extract($data);
    ob_start();
    require $viewFile;
    return ob_get_clean();
}
