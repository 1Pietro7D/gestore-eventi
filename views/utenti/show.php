<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dettaglio utente</title>
</head>
<body>
    <h1>Dettaglio utente</h1>

    <?php if ($utente): ?>
        <p><strong>ID:</strong> <?= htmlspecialchars($utente->id) ?></p>
        <p><strong>Nome:</strong> <?= htmlspecialchars($utente->nome) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($utente->email) ?></p>
        <p><strong>Ruolo:</strong> <?= htmlspecialchars($utente->ruolo) ?></p>
    <?php else: ?>
        <p>Utente non trovato.</p>
    <?php endif; ?>

    <p><a href="/utenti">Torna alla lista</a></p>
</body>
</html>
