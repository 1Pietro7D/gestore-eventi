<?php
function setSecurityHeaders() {
    //CORS
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    $GLOBALS['CSP_NONCE'] = bin2hex(random_bytes(16));

    /* ====== Content Security Policy ======
       - Apriamo le sorgenti minime necessarie
       - Aggiungiamo i domini per le immagini YouTube (i.ytimg.com) e avatar canali (yt3.ggpht.com)
       - Autorizziamo gli iframe YouTube (nocookie e standard) con frame-src esplicito
    ===================================================== */
    header(
        "Content-Security-Policy: " .
        "default-src 'self'; " . // sorgente predefinita: solo il nostro dominio
        "base-uri 'self'; " .    // limita i <base> all’origine
        "object-src 'none'; " .  // disabilita oggetti legacy (Flash, etc.)
        // ---- Stili e script con nonce (permettono inline solo se col nonce corretto) ----
        "style-src 'self' https://unpkg.com 'nonce-{$GLOBALS['CSP_NONCE']}'; " .   // CSS locali + unpkg + inline firmati da nonce
        "script-src 'self' https://unpkg.com 'nonce-{$GLOBALS['CSP_NONCE']}'; " .  // JS locale + unpkg + inline firmati da nonce
        // ---- Immagini: includiamo i domini delle mappe + unpkg + YouTube thumbnails/avatar ----
        "img-src 'self' data: " .
            "https://tile.openstreetmap.org " .
            "https://a.tile.openstreetmap.org " .
            "https://b.tile.openstreetmap.org " .
            "https://c.tile.openstreetmap.org " .
            "https://unpkg.com " .
            "https://i.ytimg.com " .          // thumbnails video YouTube (mqdefault.jpg, hqdefault.jpg, ecc.)
            "https://yt3.ggpht.com; " .       // avatar canali/profili YouTube
        // ---- Font & media ----
        "font-src 'self' https://unpkg.com; " . // font locali o serviti da unpkg
        // ---- Iframe (YouTube embed) ----
        "frame-src https://www.youtube-nocookie.com https://www.youtube.com; " . // consente gli embed YouTube
        // ---- Connessioni XHR/fetch (se servono API esterne, aggiungerle qui) ----
        "connect-src 'self'; " . // per ora permettiamo fetch/XHR solo verso noi stessi
        // ---- (Opzionale) Miglioramenti UX/sicurezza ----
        "form-action 'self'; " .           // invio form solo verso il nostro dominio
        "upgrade-insecure-requests"        // forza l’upgrade a https quando possibile
    ); // fine header CSP
     header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer-when-downgrade');
    if (!headers_sent() && isset($_SERVER['HTTPS'])) {
        // header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}