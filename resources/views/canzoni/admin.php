<nav>
    <ul class="navbar">
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['ruolo'] === 'superadmin'): ?>
        <li><a href="/canzoni">home</a></li>
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
  <h1>Gestione Canzoni</h1> <!-- titolo pagina -->

  <?php if (!empty($_SESSION['errors'])): ?> <!-- mostra eventuali errori -->
    <div class="errors"> <!-- box errori -->
      <ul> <!-- lista errori -->
        <?php foreach ($_SESSION['errors'] as $e): ?> <!-- ciclo errori -->
          <li><?= htmlspecialchars($e) ?></li> <!-- stampa errore sanificato -->
        <?php endforeach; unset($_SESSION['errors']); ?> <!-- pulisce errori -->
      </ul> <!-- fine lista -->
    </div> <!-- fine box -->
  <?php endif; ?> <!-- fine if errori -->

  <p><a class="btn" href="/canzoni/create">+ Nuova Canzone</a></p> <!-- link crea -->

  <table class="table"> <!-- tabella canzoni -->
    <thead> <!-- intestazione tabella -->
      <tr> <!-- riga intestazione -->
        <th style="width:72px">Preview</th> <!-- colonna anteprima -->
        <th>ID</th> <!-- colonna id -->
        <th>Titolo</th> <!-- colonna titolo -->
        <th>Artista</th> <!-- colonna artista -->
        <th>Utente</th> <!-- colonna proprietario -->
        <th>Azioni</th> <!-- colonna azioni -->
      </tr> <!-- fine riga -->
    </thead> <!-- fine thead -->
    <tbody> <!-- corpo tabella -->
      <?php foreach ($canzoni as $c): ?> <!-- ciclo canzoni -->
        <?php $vid = $c->getYouTubeId(); ?> <!-- estrae id youtube -->
        <tr> <!-- riga canzone -->
          <td> <!-- cella preview -->
            <?php if ($vid): ?> <!-- se esiste id -->
              <?php $thumb = "https://i.ytimg.com/vi/{$vid}/mqdefault.jpg"; ?> <!-- miniatura qualità media per tabelle -->
              <a class="thumb" href="https://www.youtube.com/watch?v=<?= htmlspecialchars($vid) ?>" target="_blank" rel="noopener"> <!-- link al video -->
                <img src="<?= htmlspecialchars($thumb) ?>" alt="Anteprima" loading="lazy" /> <!-- miniatura -->
              </a> <!-- fine link -->
            <?php else: ?> <!-- se non c'è id -->
              — <!-- placeholder -->
            <?php endif; ?> <!-- fine condizione -->
          </td> <!-- fine cella preview -->
          <td><?= (int)$c->id ?></td> <!-- stampa id -->
          <td><?= htmlspecialchars($c->titolo) ?></td> <!-- stampa titolo -->
          <td><?= htmlspecialchars($c->artista) ?></td> <!-- stampa artista -->
          <td><?= (int)$c->utente_id ?></td> <!-- stampa proprietario -->
          <td> <!-- cella azioni -->
            <a class="btn" href="/canzoni/edit/<?= (int)$c->id ?>">Modifica</a> <!-- link modifica -->
            <form action="/canzoni/delete/<?= (int)$c->id ?>" method="post" class="d-inline" onsubmit="return confirm('Eliminare definitivamente?')"> <!-- form elimina -->
              <?= csrf_field() ?> <!-- token csrf -->
              <button class="btn btn-danger" type="submit">Elimina</button> <!-- bottone -->
            </form> <!-- fine form -->
          </td> <!-- fine cella azioni -->
        </tr> <!-- fine riga -->
      <?php endforeach; ?> <!-- fine foreach -->
    </tbody> <!-- fine tbody -->
  </table> <!-- fine tabella -->
</div> <!-- fine container -->

<style>
/* stile admin con thumbnail */
.d-inline{ display : inline}
.container{max-width:1100px;margin:2rem auto;padding:1rem} /* layout container */
.table{width:100%;border-collapse:collapse} /* tabella */
.table th,.table td{border:1px solid #ddd;padding:.5rem;text-align:left;vertical-align:middle} /* celle */
.thumb{display:inline-block;width:64px;height:36px;border-radius:6px;overflow:hidden;background:#000} /* wrapper miniatura */
.thumb img{width:100%;height:100%;object-fit:cover;display:block} /* immagine coperta */
.btn{display:inline-block;background:#0d6efd;color:#fff;padding:.35rem .6rem;border-radius:6px;text-decoration:none} /* bottone blu */
.btn-danger{background:#dc3545} /* bottone rosso */
.errors{background:#fff3cd;border:1px solid #ffeeba;padding:.6rem;border-radius:6px;margin:1rem 0} /* box errori */
</style>