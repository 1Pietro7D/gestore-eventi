<h2>Utenti</h2>
<ul>
<?php foreach ($utenti as $utente): ?>
    <li><?= $utente->nome ?> - <?= $utente->ruolo ?></li>
<?php endforeach; ?>
</ul>
