<h1>Lista Utenti</h1>
<ul>
<?php foreach ($dati as $utente): ?>
    <li><?= htmlspecialchars($utente['nome']) ?> (<?= $utente['ruolo'] ?>)</li>
<?php endforeach; ?>
</ul>
