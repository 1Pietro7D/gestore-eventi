<?php require_once __DIR__ . '/../Components/header.php'; ?>
<h1>Modifica Categoria</h1>

<form action="/categorie/update/<?= $categoria->id ?>" method="POST">
    <?= csrf_field() ?>
    <label for="nome">Nome categoria:</label>
    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($categoria->nome) ?>" required>
    <br><br>
    <button type="submit">Aggiorna</button>
</form>

<a href="/categorie">?? Torna all'elenco</a>
