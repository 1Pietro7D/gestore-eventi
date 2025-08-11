<?php
function setSecurityHeaders() {
    //CORS
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // header('Content-Security-Policy: default-src \'self\'; script-src \'self\''); // mi blocca il css inline
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer-when-downgrade');
    if (!headers_sent() && isset($_SERVER['HTTPS'])) {
        // header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}