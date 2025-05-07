<h2>Utenti</h2>
<ul>
<?php foreach ($utenti as $utente): ?>
    <li><a href="/utenti/show/<?= $utente->id ?>"><?= $utente->nome ?></a> - <?= $utente->ruolo ?></li>
<?php endforeach; ?>
<p>
    <a href="/utenti/create">crea utente</a>
</p>
</ul>
