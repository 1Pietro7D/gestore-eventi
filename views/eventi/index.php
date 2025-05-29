 <?php require_once __DIR__.  ('/../Components/header.php'); ?>
<h1>Lista Eventi</h1>

<a href="/eventi/create">? Crea Nuovo Evento</a>

<?php if (empty($eventi)): ?>
    <p>Nessun evento disponibile.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titolo</th>
                <th>Data Evento</th>
                <th>Iscritti</th>
                <th>Immagine</th>
                <th>Video</th>
                <th>Categoria</th>
                <th>Prezzo</th>
                <th>Azioni</th>
               
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventi as $evento): ?>
                <tr>
                    <td><?= htmlspecialchars($evento->id) ?></td>
                    <td><?= htmlspecialchars($evento->titolo) ?></td>
                    <td><?= htmlspecialchars($evento->data_evento) ?></td>
                    <td><p>Iscritti: <?= $evento->numeroIscritti() ?> / <?= $evento->massimo_partecipanti ?></p></td>
                    <td>
                        <?php if ($evento->img_path): ?>
                            <img src="/public<?= htmlspecialchars($evento->img_path) ?>" alt="Immagine Evento" width="100">
                        <?php else: ?>
                            Nessuna immagine
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($evento->video_path): ?>
                            <a href="<?= htmlspecialchars($evento->video_path) ?>" target="_blank">Guarda Video</a>
                        <?php else: ?>
                            Nessun video
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($evento->categoria->nome) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($evento->prezzo) ?>
                    </td>
                    <td>
                        <a href="/eventi/show/<?= $evento->id ?>">Visualizza</a> |
                        <a href="/eventi/edit/<?= $evento->id ?>">Modifica</a> |
                        <form action="/eventi/delete/<?= $evento->id ?>" method="POST" style="display:inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo evento?');">
                            <button type="submit">Elimina</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
