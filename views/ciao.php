<h2>Utenti</h2>
<ul>
<?php foreach ($dati as $utente): ?>
    <li><?= $utente->nome ?> - <?= $utente->ruolo ?></li>
<?php endforeach; ?>
</ul>
