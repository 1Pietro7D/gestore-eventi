<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Modifica utente</title>
</head>
<body>
     <?php require_once __DIR__.  ('/../Components/header.php'); ?>
    <h1>Modifica utente</h1>

    <?php if ($utente): ?>
        <form action="/utenti/update/<?= $utente->id ?>" method="POST">
            <p>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($utente->nome) ?>" required>
            </p>
            <p>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($utente->email) ?>" required>
            </p>
            <p>
                <label for="password">Password (lascia vuoto per non cambiare):</label>
                <input type="password" name="password" id="password">
            </p>
            <p>
                <label for="ruolo">Ruolo:</label>
                <select name="ruolo" id="ruolo">
                    <option value="user" <?= $utente->ruolo === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $utente->ruolo === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </p>
            <button type="submit">Aggiorna utente</button>
        </form>
    <?php else: ?>
        <p>Utente non trovato.</p>
    <?php endif; ?>

    <p><a href="/utenti">Torna alla lista</a></p>
</body>
</html>
