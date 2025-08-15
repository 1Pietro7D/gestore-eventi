<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Evento</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""
          nonce="<?= $GLOBALS['CSP_NONCE'] ?>"/>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""
            nonce="<?= $GLOBALS['CSP_NONCE'] ?>"></script>
    
    <!-- CSS inline protetto -->
    <style nonce="<?= $GLOBALS['CSP_NONCE'] ?>">
        #map { height: 180px; }
        .error{
            color : red;
        }
        .success{
            color : green
        }
        .d-inline{
            display: inline;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../Components/header.php'; ?>

    <h1>Dettagli Evento</h1>

    <p><strong>ID:</strong> <?= htmlspecialchars($evento->id) ?></p>
    <p><strong>Titolo:</strong> <?= htmlspecialchars($evento->titolo) ?></p>
    <p><strong>Descrizione:</strong><br><?= nl2br(htmlspecialchars($evento->descrizione)) ?></p>
    <p><strong>Data Evento:</strong> <?= htmlspecialchars($evento->data_evento) ?></p>
    <p><strong>Categoria:</strong> <?= htmlspecialchars($evento->categoria->nome) ?></p>
    <p><strong>Prezzo:</strong> € <?= number_format($evento->prezzo, 2, ',', '.') ?></p>
    <p>Iscritti: <?= $evento->numeroIscritti() ?> / <?= $evento->massimo_partecipanti ?></p>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="error">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="success">
            <p><?= htmlspecialchars($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user'])): ?>
        <?php if ($evento->isUtenteIscritto($_SESSION['user']['id'])): ?>
            <form method="POST" action="/eventi/<?= $evento->id ?>/annulla-iscrizione">
                <?= csrf_field() ?>
                <button type="submit">Cancella iscrizione</button>
            </form>
        <?php else: ?>
            <form method="POST" action="/eventi/<?= $evento->id ?>/iscriviti">
                <?= csrf_field() ?>
                <button type="submit">Iscriviti all'evento</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p><a href="/login">Accedi</a> per iscriverti a questo evento.</p>
    <?php endif; ?>

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

    <?php if (
        (isset($_SESSION['user']) && $_SESSION['user']['ruolo'] === 'superadmin') ||
        (isset($_SESSION['user']) && $_SESSION['user']['id'] === $evento->utente_id)
    ): ?>
        <a href="/eventi/edit/<?= $evento->id ?>">Modifica Evento</a> |
        <form action="/eventi/delete/<?= $evento->id ?>" method="POST" class="d-inline" onsubmit="return confirm('Sei sicuro di voler eliminare questo evento?');">
            <button type="submit">Elimina Evento</button>
        </form>
    <?php endif; ?>

    <br><br>
    <a href="/">Torna alla home</a>

    <div id="map"></div>
    <?php if (!is_null($evento->latitudine) && !is_null($evento->longitudine)): ?>
        <script nonce="<?= $GLOBALS['CSP_NONCE'] ?>">
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
        <script nonce="<?= $GLOBALS['CSP_NONCE'] ?>">
            map = L.map('map').setView([41.9028, 12.4964], 5);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
        </script>
    <?php endif; ?>

    <form method="POST" action="/carrello/aggiungi/<?= $evento->id ?>">
        <label for="quantità">Quantità:</label>
        <input type="number" id="quantità" name="quantita" value="1" min="1" max="10" required>
        <button type="submit">Aggiungi al carrello</button>
    </form>

    <?php if (FlashMessages::has()): ?>
        <?php foreach (FlashMessages::get() as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <div class="alert alert-<?= htmlspecialchars($type) ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>