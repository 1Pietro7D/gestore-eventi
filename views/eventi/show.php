<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
     <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        #map { height: 180px; }
    </style>
</head>
<body>
    <?php require_once __DIR__.  ('/../Components/header.php'); ?>
    <h1>Dettagli Evento</h1>

    <p><strong>ID:</strong> <?= htmlspecialchars($evento->id) ?></p>
    <p><strong>Titolo:</strong> <?= htmlspecialchars($evento->titolo) ?></p>
    <p><strong>Descrizione:</strong><br><?= nl2br(htmlspecialchars($evento->descrizione)) ?></p>
    <p><strong>Data Evento:</strong> <?= htmlspecialchars($evento->data_evento) ?></p>
    <p><strong>Categoria:</strong> <?= htmlspecialchars($evento->categoria->nome) ?></p>
    <p><strong>Prezzo:</strong>€ <?= number_format($evento->prezzo, 2, ',', '.') ?></p>
    <p>Iscritti: <?= $evento->numeroIscritti() ?> / <?= $evento->massimo_partecipanti ?></p>
    <?php if (!empty($_SESSION['errors'])): ?>
        <div style="color: red;">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>
            
    <?php if (!empty($_SESSION['success'])): ?>
        <div style="color: green;">
            <p><?= htmlspecialchars($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>


    <?php if (isset($_SESSION['user'])): ?>
        <?php if ($evento->isUtenteIscritto($_SESSION['user']['id'])): ?>
            <form method="POST" action="/eventi/<?= $evento->id ?>/annulla-iscrizione">
                <button type="submit">Cancella iscrizione</button>
            </form>
        <?php else: ?>
            <form method="POST" action="/eventi/<?= $evento->id ?>/iscriviti">
                <button type="submit">Iscriviti all'evento</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p><a href="/login">Accedi</a> per iscriverti a questo evento.</p>
    <?php endif; ?>



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

    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $evento->utente_id): ?>
        <a href="/eventi/edit/<?= $evento->id ?>">Modifica Evento</a> |
        <form action="/eventi/delete/<?= $evento->id ?>" method="POST" style="display:inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo evento?');">
            <button type="submit">Elimina Evento</button>
        </form>
    <?php endif; ?>


    <br><br>
    <a href="/">Torna alla home</a>


     <div id="map"></div>
    <?php if (!is_null($evento->latitudine) && !is_null($evento->longitudine)): ?>
        <script>
            const map = L.map('map').setView([<?= $evento->latitudine ?>, <?= $evento->longitudine ?>], 13);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
        
            L.marker([<?= $evento->latitudine ?>, <?= $evento->longitudine ?>])
                .addTo(map)
                .bindPopup("<?= htmlspecialchars($evento->titolo) ?>")
                .openPopup();
        </script>
    <?php else: ?>
        <script>
            
            map = L.map('map').setView([41.9028, 12.4964], 5); // vista generale su Italia
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
        </script>
    <?php endif; ?>
</body>
</html>
