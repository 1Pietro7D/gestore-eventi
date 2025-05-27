<h1>Registrazione</h1>
<?php
    if (!empty($_SESSION['errors'])) {
        echo '<ul style="color: red;">';
        foreach ($_SESSION['errors'] as $campo => $errore) {
            echo "<li><strong>$campo:</strong> $errore</li>";
        }
        echo '</ul>';
        unset($_SESSION['errors']); // Pulisci dopo aver mostrato
    }
?>
<form method="POST" action="/register">
    <label>Nome: <input type="text" name="nome" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Ruolo: 
        <select name="ruolo">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </label><br>
    <button>Registrati</button>
</form>
