<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dettaglio utente</title>
</head>
<body>
     <?php require_once __DIR__.  ('/../Components/header.php'); ?>
    <h1>Dettaglio utente</h1>

    <?php if ($utente): ?>
        <p><strong>ID:</strong> <?= $utente->id ?></p>
        <p><strong>Nome:</strong> <?= htmlspecialchars($utente->nome) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($utente->email) ?></p>
        <p><strong>Ruolo:</strong> <?= htmlspecialchars($utente->ruolo) ?></p>
        <p>
            <a href="/utenti/edit/<?= $utente->id ?>">Modifica utente</a>
        </p>

        <form action="/utenti/delete/<?= $utente->id ?>" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo utente?');">
            <button type="submit">Elimina utente</button>
        </form>
    <?php else: ?>
        <p>Utente non trovato.</p>
    <?php endif; ?>

    <p><a href="/utenti">Torna alla lista</a></p>
</body>
</html>
