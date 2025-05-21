<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Crea nuovo utente</title>
</head>
<body>
     <?php require_once __DIR__.  ('/../Components/header.php'); ?>
    <h1>Crea nuovo utente</h1>

    <form action="/utenti/store" method="POST">
        <p>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <label for="ruolo">Ruolo:</label>
            <select name="ruolo" id="ruolo">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </p>
        <button type="submit">Crea utente</button>
    </form>

    <p><a href="/utenti">Torna alla lista</a></p>
</body>
</html>
