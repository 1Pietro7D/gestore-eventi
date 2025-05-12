<h1>Dettagli Evento</h1>

<p><strong>ID:</strong> <?= htmlspecialchars($evento->id) ?></p>
<p><strong>Titolo:</strong> <?= htmlspecialchars($evento->titolo) ?></p>
<p><strong>Descrizione:</strong><br><?= nl2br(htmlspecialchars($evento->descrizione)) ?></p>
<p><strong>Data Evento:</strong> <?= htmlspecialchars($evento->data_evento) ?></p>

<p><strong>Immagine:</strong><br>
<?php if ($evento->img_path): ?>
    <img src="/public<?= htmlspecialchars($evento->img_path) ?>" alt="Immagine Evento" width="300">
<?php else: ?>
    Nessuna immagine disponibile.
<?php endif; ?>
</p>

<p><strong>Video:</strong><br>
<?php if ($evento->video_path): ?>
    <video width="320" height="240" controls>
        <source src="/public<?= htmlspecialchars($evento->video_path) ?>">
        Il tuo browser non supporta il tag video.
    </video>
<?php else: ?>
    Nessun video disponibile.
<?php endif; ?>
</p>

<a href="/eventi/edit/<?= $evento->id ?>">?? Modifica Evento</a> |
<form action="/eventi/delete/<?= $evento->id ?>" method="POST" style="display:inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo evento?');">
    <button type="submit">??? Elimina Evento</button>
</form>

<br><br>
<a href="/eventi">?? Torna alla lista eventi</a>
