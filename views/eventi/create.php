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
            <label for="prezzo">Prezzo:</label><br>
            <input type="number" id="prezzo" name="prezzo" min="0" value="0" step="0.01" required>
        </div>
        <div>
            <label for="massimo_partecipanti">Numero massimo di partecipanti:</label><br>
            <input type="number" id="massimo_partecipanti" name="massimo_partecipanti" min="1" value="50" required>
        </div>
        <div>
            <label for="img_file">Carica Immagine:</label><br>
            <input type="file" id="img_file" name="img_file" accept="image/*">
        </div>

        <div>
            <label for="video_file">Carica Video:</label><br>
            <input type="file" id="video_file" name="video_file" accept="video/*">
        </div>
        <div>
            <label>Seleziona la posizione sulla mappa:</label>
            <div id="map" style="height: 300px; margin-bottom: 1rem;"></div>

            <!-- Campi nascosti -->
            <input type="hidden" name="latitudine" id="latitudine">
            <input type="hidden" name="longitudine" id="longitudine">
        </div>    
        <button type="submit">?? Salva Evento</button>
    </form>

    <a href="/eventi">?? Torna alla lista eventi</a>

<script>
    const defaultCoords = [41.9028, 12.4964]; // Roma
    const map = L.map('map').setView(defaultCoords, 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    map.on('click', function(e) {
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
 
