<?php
function setSecurityHeaders() {
    // header('Content-Security-Policy: default-src \'self\'; script-src \'self\''); // mi blocca il css inline
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer-when-downgrade');
    if (!headers_sent() && isset($_SERVER['HTTPS'])) {
        // header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}