<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
     <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    

</head>
<body>
    <?php require_once __DIR__ . '/../Components/header.php'; ?>
    <h1>Modifica Evento</h1>

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

    <form action="/eventi/update/<?= $evento->id ?>" method="POST" enctype="multipart/form-data">
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

        <div>
            <label for="categoria_id">Categoria:</label><br>
            <select name="categoria_id" id="categoria_id" required>
                <?php foreach ($categorie as $categoria): ?>
                    <option value="<?= $categoria->id ?>" <?= $evento->categoria_id === $categoria->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria->nome) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="massimo_partecipanti">Numero massimo di partecipanti:</label><br>
            <input type="number" id="massimo_partecipanti" name="massimo_partecipanti"
                   value="<?= (int)$evento->massimo_partecipanti ?>" min="1" required>
        </div>

        <div>
            <label>Seleziona la posizione sulla mappa:</label>
            <div id="map" style="height: 300px; margin-bottom: 1rem;"></div>
            <input type="hidden" name="latitudine" id="latitudine" value="<?= $evento->latitudine ?>">
            <input type="hidden" name="longitudine" id="longitudine" value="<?= $evento->longitudine ?>">
        </div>

        <h3>Aggiorna Immagine o Video</h3>

        <div>
            <label for="img_file">Nuova Immagine:</label><br>
            <input type="file" id="img_file" name="img_file" accept="image/*">
        </div>

        <div>
            <label for="video_file">Nuovo Video:</label><br>
            <input type="file" id="video_file" name="video_file" accept="video/*">
        </div>

        <button type="submit">Salva Modifiche</button>
    </form>

    <h3>Immagine e Video Attuali</h3>

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

    <br><a href="/eventi/show/<?= $evento->id ?>">Torna ai dettagli evento</a>


    <script>
        const lat = <?= $evento->latitudine ?? 'null' ?>;
        const lng = <?= $evento->longitudine ?? 'null' ?>;

        const defaultLat = 41.9028; // Roma
        const defaultLng = 12.4964;

        const map = L.map('map').setView(
            [lat ?? defaultLat, lng ?? defaultLng],
            13
        );

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        if (lat !== null && lng !== null) {
            marker = L.marker([lat, lng]).addTo(map);
        }

        map.on('click', function (e) {
            const { lat, lng } = e.latlng;

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }

            document.getElementById('latitudine').value = lat;
            document.getElementById('longitudine').value = lng;
        });
    </script>

</body>
</html>
