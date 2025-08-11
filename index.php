<?php
require_once __DIR__ . '/core/AutoLoader.php';
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();
require_once 'core/SecurityHeaders.php';
setSecurityHeaders();

Csrf::init();

// ==========================================
// 🎯 GESTIONE SICURA FILE STATICI (es. /resources?file=style/generics.css)
// ==========================================
if (isset($_GET['file'])) {
    $requestedFile = $_GET['file'];
    $resourcePath = realpath(__DIR__ . '/resources/' . $requestedFile);

    $isInsideResources = $resourcePath && str_starts_with($resourcePath, realpath(__DIR__ . '/resources'));

    if (!$isInsideResources || !file_exists($resourcePath)) {
        http_response_code(404);
        echo 'File not found';
        exit;
    }

    // 🔐 Verifica che la richiesta provenga da una tua vista
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $host = $_SERVER['HTTP_HOST'];
    $token = $_GET['token'] ?? '';

    $csrfValid = method_exists('Csrf', 'check') ? Csrf::check($token) : false;

    if (!str_contains($referer, $host) && !$csrfValid) {
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

// ==========================
// 🎯 Normale flusso app
// ==========================
require_once 'core/Render.php';
$routes = require 'routes.php';
$router = new Router($routes);
$router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
