<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>

    <h1>Benvenuto nel Gestore Eventi</h1>

    <?php if (isset($_SESSION['user'])): ?>
        <p>Ciao <?= htmlspecialchars($_SESSION['user']['nome']) ?>!</p>
        <p>Sei autenticato come <strong><?= htmlspecialchars($_SESSION['user']['ruolo']) ?></strong></p>
        <a href="/logout">Logout</a> |
        <form method="POST" action="/delete" style="display:inline">
            <button type="submit">Cancella Registrazione</button>
        </form>
    <?php else: ?>
        <p>Non sei autenticato.</p>
        <a href="/login">Login</a> |
        <a href="/register">Registrati</a>
    <?php endif; ?>

</body>
</html>
