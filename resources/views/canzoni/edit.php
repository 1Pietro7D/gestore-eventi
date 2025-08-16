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
  <h1>Modifica Canzone</h1> <!-- titolo pagina -->

  <?php if (!empty($_SESSION['errors'])): ?> <!-- stampa errori se presenti -->
    <div class="errors"> <!-- box errori -->
      <ul> <!-- lista errori -->
        <?php foreach ($_SESSION['errors'] as $e): ?> <!-- itera errori -->
          <li><?= htmlspecialchars($e) ?></li> <!-- stampa errore sanificato -->
        <?php endforeach; unset($_SESSION['errors']); ?> <!-- pulisce errori -->
      </ul> <!-- fine lista -->
    </div> <!-- fine box -->
  <?php endif; ?> <!-- chiusura blocco errori -->

  <form action="/canzoni/update/<?= (int)$canzone->id ?>" method="POST"> <!-- form invio aggiornamento -->
    <?= csrf_field() ?> <!-- campo nascosto CSRF -->

    <label for="titolo">Titolo</label> <!-- etichetta -->
    <input id="titolo" type="text" name="titolo" value="<?= htmlspecialchars($canzone->titolo) ?>" required> <!-- input con valore -->

    <label for="artista">Artista</label> <!-- etichetta -->
    <input id="artista" type="text" name="artista" value="<?= htmlspecialchars($canzone->artista) ?>" required> <!-- input con valore -->

    <label for="youtube_url">URL YouTube</label> <!-- etichetta -->
    <input id="youtube_url" type="url" name="youtube_url" value="<?= htmlspecialchars($canzone->youtube_url) ?>" required> <!-- input con valore -->

    <input type="hidden" name="utente_id" value="<?= (int)$canzone->utente_id ?>"> <!-- mantiene owner corrente (server lo ignora) -->

    <?php $vid = $canzone->getYouTubeId(); ?> <!-- calcola ID per anteprima iniziale -->
    <div id="preview" class="ratio-16x9" style="<?= $vid ? '' : 'display:none' ?>"> <!-- mostra se ID presente -->
      <iframe
        title="Anteprima YouTube" <!-- titolo accessibile -->
        frameborder="0" <!-- no bordo -->
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" <!-- abilita feature -->
        allowfullscreen <!-- fullscreen -->
        <?php if ($vid): ?>src="https://www.youtube-nocookie.com/embed/<?= htmlspecialchars($vid) ?>"<?php endif; ?> <!-- src iniziale se disponibile -->
      ></iframe> <!-- chiusura iframe -->
    </div> <!-- fine anteprima -->

    <button type="submit" class="btn">Aggiorna</button> <!-- bottone submit -->
  </form> <!-- fine form -->
</div> <!-- fine container -->

<script nonce="<?= $GLOBALS['CSP_NONCE'] ?? '' ?>"> // JS inline con nonce per rispettare la CSP
  // Stessa logica di create per aggiornare l'anteprima al volo durante l'editing
  const extractId = (url) => { // funzione estrazione ID
    if (!url) return null; // se vuoto → null
    const ps = [/[?&]v([\w-]{11})/i,/youtu\.be\/([\w-]{11})/i,/\/embed\/([\w-]{11})/i]; // pattern noti
    for (const p of ps){ const m = url.match(p); if (m) return m[1]; } // ritorna alla prima corrispondenza
    return null; // niente corrispondenza
  }; // fine extractId
  const urlInput = document.getElementById('youtube_url'); // campo url
  const preview = document.getElementById('preview'); // contenitore anteprima
  const frame = preview.querySelector('iframe'); // iframe
  urlInput.addEventListener('input', () => { // aggiorna on input
    const id = extractId(urlInput.value.trim()); // tenta estrazione
    if (id){ frame.src = `https://www.youtube-nocookie.com/embed/${id}`; preview.style.display = 'block'; } // mostra con src
    else { frame.removeAttribute('src'); preview.style.display = 'none'; } // nasconde se non valido
  }); // fine listener
</script> <!-- fine script anteprima edit -->

<style> /* stile locale edit */
  .container{max-width:700px;margin:2rem auto;padding:1rem} /* layout */
  label{display:block;margin:.6rem 0 .2rem;font-weight:600} /* etichette */
  input[type="text"],input[type="url"]{width:100%;padding:.5rem;border:1px solid #ccc;border-radius:6px} /* input */
  .btn{margin-top:1rem;display:inline-block;background:#0d6efd;color:#fff;padding:.5rem .8rem;border-radius:6px;border:0;cursor:pointer} /* bottone */
  .ratio-16x9{position:relative;width:100%;padding-top:56.25%;margin-top:1rem} /* anteprima 16:9 */
  .ratio-16x9 iframe{position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:10px} /* iframe */
  .errors{background:#fff3cd;border:1px solid #ffeeba;padding:.6rem;border-radius:6px;margin:1rem 0} /* box errori */
</style> <!-- fine stile edit -->

