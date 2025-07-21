<?php require_once __DIR__ . '/../Components/header.php'; ?>
<h1>Nuova Categoria</h1>

<form action="/categorie/store" method="POST">
    <?= csrf_field() ?>
    <label for="nome">Nome categoria:</label>
    <input type="text" id="nome" name="nome" required>
    <br><br>
    <button type="submit">Salva</button>
</form>

<a href="/categorie"> Torna all'elenco</a>
