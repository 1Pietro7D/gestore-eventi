<h1>Login</h1>
<?php
    if (!empty($_SESSION['errors'])) {
        echo '<ul style="color: red;">';
        foreach ($_SESSION['errors'] as $campo => $errore) {
            echo "<li><strong>$campo</strong> $errore</li>";
        }
        echo '</ul>';
        unset($_SESSION['errors']); // Pulisci dopo aver mostrato
    }
?>
<form method="POST" action="/login">
    <?= csrf_field() ?>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button>Login</button>
</form>
