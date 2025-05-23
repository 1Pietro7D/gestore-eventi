 <?php require_once __DIR__.  ('/../Components/header.php'); ?>
<h1>Crea Nuovo Evento</h1>
<?php if (!empty($_SESSION['errors'])): ?>
    <div style="color: red;">
        <ul>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form action="/eventi/store" method="POST" enctype="multipart/form-data">
    <div>
        <label for="titolo">Titolo:</label><br>
        <input type="text" id="titolo" name="titolo" required>
    </div>

    <div>
        <label for="descrizione">Descrizione:</label><br>
        <textarea id="descrizione" name="descrizione" rows="5" cols="40"></textarea>
    </div>

    <div>
        <label for="data_evento">Data Evento:</label><br>
        <input type="date" id="data_evento" name="data_evento" required>
    </div>
    <div>
    <label for="categoria_id">Categoria:</label><br>
    <select name="categoria_id" id="categoria_id" required>
        <option value="">-- Seleziona una categoria --</option>
        <?php foreach ($categorie as $categoria): ?>
            <option value="<?= $categoria->id ?>"><?= htmlspecialchars($categoria->nome) ?></option>
        <?php endforeach; ?>
    </select>
</div>

    <div>
        <label for="img_file">Carica Immagine:</label><br>
        <input type="file" id="img_file" name="img_file" accept="image/*">
    </div>

    <div>
        <label for="video_file">Carica Video:</label><br>
        <input type="file" id="video_file" name="video_file" accept="video/*">
    </div>

    <button type="submit">?? Salva Evento</button>
</form>

<a href="/eventi">?? Torna alla lista eventi</a>
