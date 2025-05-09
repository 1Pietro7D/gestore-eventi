<h1>Modifica Evento</h1>

<form action="/eventi/update/<?= $evento->id ?>" method="POST">
    <div>
        <label for="titolo">Titolo:</label><br>
        <input type="text" id="titolo" name="titolo" value="<?= htmlspecialchars($evento->titolo) ?>" required>
    </div>

    <div>
        <label for="descrizione">Descrizione:</label><br>
        <textarea id="descrizione" name="descrizione" rows="5" cols="40"><?= htmlspecialchars($evento->descrizione) ?></textarea>
    </div>

    <div>
        <label for="data_evento">Data Evento:</label><br>
        <input type="date" id="data_evento" name="data_evento" value="<?= htmlspecialchars($evento->data_evento) ?>" required>
    </div>

    <button type="submit">?? Salva Modifiche</button>
</form>

<h3>Immagine e Video Attuali</h3>

<p><strong>Immagine:</strong><br>
<?php if ($evento->img_path): ?>
    <img src="<?= htmlspecialchars($evento->img_path) ?>" alt="Immagine Evento" width="300">
<?php else: ?>
    Nessuna immagine disponibile.
<?php endif; ?>
</p>

<p><strong>Video:</strong><br>
<?php if ($evento->video_path): ?>
    <video width="320" height="240" controls>
        <source src="<?= htmlspecialchars($evento->video_path) ?>">
        Il tuo browser non supporta il tag video.
    </video>
<?php else: ?>
    Nessun video disponibile.
<?php endif; ?>
</p>

<h3>Aggiorna Immagine o Video</h3>

<form action="/eventi/update-media/<?= $evento->id ?>" method="POST" enctype="multipart/form-data">
    <div>
        <label for="img_file">Nuova Immagine:</label><br>
        <input type="file" id="img_file" name="img_file" accept="image/*">
    </div>

    <div>
        <label for="video_file">Nuovo Video:</label><br>
        <input type="file" id="video_file" name="video_file" accept="video/*">
    </div>

    <button type="submit">?? Aggiorna Media</button>
</form>

<br><a href="/eventi/show/<?= $evento->id ?>">?? Torna ai dettagli evento</a>
