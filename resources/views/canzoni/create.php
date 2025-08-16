<nav>
    <ul class="navbar">
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['ruolo'] === 'superadmin'): ?>
        <li><a href="/canzoni">home</a></li>
        <li><a href="/canzoni/admin">Admin</a></li>
    <?php endif; ?>
    </ul>
</nav>

<style nonce="<?= htmlspecialchars($GLOBALS['CSP_NONCE']) ?>">
    nav{
        display: flex;
        justify-content: space-between;
    }
    .navbar{
        display: flex;
        gap: 2rem;
        justify-content: space-between;
    }
</style>


<div class="container"> <!-- contenitore principale -->
  <h1>Nuova Canzone</h1> <!-- titolo pagina -->

  <?php if (!empty($_SESSION['errors'])): ?> <!-- stampa errori se presenti -->
    <div class="errors"> <!-- box errori -->
      <ul> <!-- lista errori -->
        <?php foreach ($_SESSION['errors'] as $e): ?> <!-- itera errori -->
          <li><?= htmlspecialchars($e) ?></li> <!-- stampa errore sanificato -->
        <?php endforeach; unset($_SESSION['errors']); ?> <!-- pulisce errori -->
      </ul> <!-- fine lista -->
    </div> <!-- fine box -->
  <?php endif; ?> <!-- chiusura blocco errori -->

  <form action="/canzoni/store" method="POST"> <!-- form invio creazione -->
    <?= csrf_field() ?> <!-- campo nascosto CSRF -->

    <label for="titolo">Titolo</label> <!-- etichetta campo titolo -->
    <input id="titolo" type="text" name="titolo" required> <!-- input testo obbligatorio -->

    <label for="artista">Artista</label> <!-- etichetta campo artista -->
    <input id="artista" type="text" name="artista" required> <!-- input testo obbligatorio -->

    <label for="youtube_url">URL YouTube</label> <!-- etichetta url youtube -->
    <input id="youtube_url" type="url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." required> <!-- input url obbligatorio con placeholder -->

    <input type="hidden" name="utente_id" value="<?= $_SESSION['user']['id'] ?? '' ?>"> <!-- owner attuale (non fidarsi lato server) -->

    <div id="preview" class="ratio-16x9" style="display:none"> <!-- contenitore anteprima nascosto -->
      <iframe title="Anteprima YouTube" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe> <!-- iframe vuoto, verrà popolato da JS -->
    </div> <!-- fine preview -->

    <button type="submit" class="btn">Salva</button> <!-- bottone invio -->
  </form> <!-- fine form -->
</div> <!-- fine container -->

<script nonce="<?= $GLOBALS['CSP_NONCE'] ?? '' ?>"> // JS inline con nonce per rispettare la CSP
  // Funzione che prova ad estrarre l'ID YouTube da vari formati di URL
  const extractId = (url) => { // definisce funzione estrazione
    if (!url) return null; // se url vuoto ritorna null
    const patterns = [ // espressioni regolari per vari formati
      /[?&]v([\w-]{11})/i, // formato classico v=ID (nota: escape doppio solo in editor)
      /youtu\.be\/([\w-]{11})/i, // formato youtu.be/ID
      /\/embed\/([\w-]{11})/i // formato /embed/ID
    ]; // fine array pattern
    for (const p of patterns) { // itera ogni pattern
      const m = url.match(p); // applica il pattern
      if (m) return m[1]; // se c'è match ritorna l'ID
    } // fine ciclo
    return null; // nessun match
  }; // fine funzione extractId

  // Gestisce aggiornamento anteprima quando cambia l'input URL
  const urlInput = document.getElementById('youtube_url'); // prende riferimento all'input
  const preview = document.getElementById('preview'); // contenitore anteprima
  const frame = preview.querySelector('iframe'); // iframe interno

  urlInput.addEventListener('input', () => { // ascolta cambi input
    const id = extractId(urlInput.value.trim()); // prova a ottenere ID
    if (id) { // se ID valido
      frame.src = `https://www.youtube-nocookie.com/embed/${id}`; // setta src dell'iframe
      preview.style.display = 'block'; // mostra anteprima
    } else { // altrimenti
      frame.removeAttribute('src'); // rimuove src
      preview.style.display = 'none'; // nasconde anteprima
    } // fine if
  }); // fine listener
</script> <!-- fine script anteprima create -->

<style> /* stile locale create */
  .container{max-width:700px;margin:2rem auto;padding:1rem} /* layout stretto */
  label{display:block;margin:.6rem 0 .2rem;font-weight:600} /* etichette */
  input[type="text"],input[type="url"]{width:100%;padding:.5rem;border:1px solid #ccc;border-radius:6px} /* input */
  .btn{margin-top:1rem;display:inline-block;background:#0d6efd;color:#fff;padding:.5rem .8rem;border-radius:6px;border:0;cursor:pointer} /* bottone */
  .ratio-16x9{position:relative;width:100%;padding-top:56.25%;margin-top:1rem} /* anteprima 16:9 */
  .ratio-16x9 iframe{position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:10px} /* iframe */
  .errors{background:#fff3cd;border:1px solid #ffeeba;padding:.6rem;border-radius:6px;margin:1rem 0} /* box errori */
</style> <!-- fine stile create -->
