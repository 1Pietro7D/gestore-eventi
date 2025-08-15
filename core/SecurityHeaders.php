<?php
function setSecurityHeaders() {
    //CORS
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    $GLOBALS['CSP_NONCE'] = bin2hex(random_bytes(16));

    header(
        "Content-Security-Policy: " .
        "default-src 'self'; " .
        "style-src 'self' https://unpkg.com 'nonce-{$GLOBALS['CSP_NONCE']}'; " .
        "script-src 'self' https://unpkg.com 'nonce-{$GLOBALS['CSP_NONCE']}'; " .
        "img-src 'self' data: https://tile.openstreetmap.org https://a.tile.openstreetmap.org https://b.tile.openstreetmap.org https://c.tile.openstreetmap.org https://unpkg.com; " .
        "font-src 'self' https://unpkg.com;"
    );
    //header('Content-Security-Policy: default-src \'self\'; script-src \'self\''); // mi blocca il css inline
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer-when-downgrade');
    if (!headers_sent() && isset($_SERVER['HTTPS'])) {
        // header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}