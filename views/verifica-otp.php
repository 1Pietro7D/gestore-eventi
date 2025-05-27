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
<form method="POST" action="/verifica-otp">
    <label for="otp">Inserisci il codice OTP ricevuto via email:</label>
    <input type="text" name="otp" maxlength="6" required>
    <button type="submit">Verifica</button>
</form>
