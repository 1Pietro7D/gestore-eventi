<?php

class HomeController {
    public function index($request) {
        echo "<h1>Benvenuto, {$_SESSION['user']['nome']}</h1>";
        echo "<p>Sei autenticato come {$_SESSION['user']['ruolo']}</p>";
        echo "<a href='/mini3/logout'>Logout</a> | <form method='POST' action='/mini3/delete' style='display:inline'><button>Cancella Registrazione</button></form>";
    }
}
