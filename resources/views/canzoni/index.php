<header class="topbar"> <!-- barra superiore molto chiara ma semi-trasparente -->
  <a href="/" class="brand"> <!-- link al root del sito come brand -->
    <img 
      src="resources/assets/demons_cry_2_logo_no_back.svg" 
      alt="Logo MM" 
      class="logo" 
      loading="eager" 
      decoding="async"  
    > <!-- chiusura img -->
  </a> <!-- chiusura link brand -->

  <div class="navgroup"> <!-- wrapper che raggruppa i menu a destra -->
    <nav class="mainnav"> <!-- navigazione principale visibile a tutti -->
      <ul> <!-- lista link principali -->
        <li><a href="/canzoni">Home</a></li> <!-- link Home alla lista canzoni -->
        <li><a href="/canzoni#songs">Songs</a></li> <!-- link alla rotta canzoni/*songs con ancora verso il container id="songs" -->
      </ul> <!-- chiusura lista -->
    </nav> <!-- fine nav principale -->

    <?php if (isset($_SESSION['user']) && $_SESSION['user']['ruolo'] === 'superadmin'): ?> <!-- se utente superadmin mostra nav admin -->
      <nav class="navbar"> <!-- navigazione riservata ai superadmin -->
        <ul> <!-- lista di voci di menu admin -->
          <li><a href="/canzoni/admin">Admin</a></li> <!-- link area admin -->
        </ul> <!-- chiusura lista -->
      </nav> <!-- chiusura nav -->
    <?php endif; ?> <!-- fine condizione superadmin -->
  </div> <!-- fine navgroup -->
</header> <!-- fine header -->

<div class="intro"> <!-- blocco introduttivo centrato al 50% pagina -->
  <h1>IL SITO DI MM</h1> <!-- titolo principale centrato (vedi stile) -->
  <p> <!-- paragrafo introduttivo con testo allineato a sinistra -->
    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab id vel ducimus, accusamus omnis eveniet similique provident molestiae. Rem omnis totam numquam labore dolorem sapiente aut maiores dolores dolor enim.
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus tenetur voluptatum nam non tempora voluptatibus, dolor nesciunt molestias architecto, asperiores pariatur placeat accusantium possimus officiis consequuntur ipsam recusandae repellat quis!
  </p> <!-- chiusura paragrafo -->
</div> <!-- fine intro -->

<!-- CONTENUTO PRINCIPALE -->
<div id="songs" class="container"> <!-- contenitore principale (50% pagina) con id 'songs' per l'ancora del link -->
  <h1>🎵 Canzoni</h1> <!-- titolo sezione centrato (vedi stile) -->
  <div class="grid"> <!-- griglia di card responsive -->
    <?php foreach ($canzoni as $c): ?> <!-- ciclo su ogni canzone -->
      <div class="card"> <!-- card singola canzone -->
        <h3><?= htmlspecialchars($c->titolo) ?> <!-- stampa titolo sanificato -->
          - <small><?= htmlspecialchars($c->artista) ?></small> <!-- stampa artista sanificato -->
        </h3> <!-- chiusura titolo -->
        <?php $vid = $c->getYouTubeId(); ?> <!-- estrae l'ID di YouTube dall'URL -->
        <?php if ($vid): ?> <!-- se l'ID è valido -->
          <div class="ratio-16x9"> <!-- wrapper per rapporto 16:9 -->
            <iframe
              src="https://www.youtube-nocookie.com/embed/<?= htmlspecialchars($vid) ?>" <!-- usa dominio no-cookie per privacy -->
              title="Anteprima YouTube" <!-- titolo iframe accessibile -->
              frameborder="0" <!-- rimuove bordo -->
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" <!-- abilita feature -->
              allowfullscreen <!-- consente fullscreen -->
            ></iframe> <!-- chiusura iframe -->
          </div> <!-- fine wrapper 16:9 -->
        <?php else: ?> <!-- se non c'è ID -->
          <p>Nessuna anteprima disponibile.</p> <!-- fallback di testo -->
        <?php endif; ?> <!-- chiusura condizione anteprima -->
      </div> <!-- fine card singola -->
    <?php endforeach; ?> <!-- fine foreach canzoni -->
  </div> <!-- fine griglia -->
</div> <!-- fine container -->

<!-- STILI (con CSP nonce) -->
<style nonce="<?= htmlspecialchars($GLOBALS['CSP_NONCE'] ?? '', ENT_QUOTES, 'UTF-8') ?>"> /* blocco di stile con nonce per rispettare la CSP */

  /* ====== Reset minimo e tipografia ====== */
  * { box-sizing: border-box; } /* gestione dimensioni coerente */
  html, body { margin: 0; padding: 0; } /* azzera margini/padding di default */
  body{ 
    background-color: black; /* sfondo nero del sito */
    background-image: url('/resources/assets/istockphoto-184601291-612x612.jpg'); /* immagine di sfondo pagina */
    background-repeat: no-repeat; /* evita ripetizione */
    background-size: cover; /* scala l'immagine a coprire tutto */
    background-attachment: fixed; /* fissa l'immagine durante lo scroll */
    color: white; /* testo in bianco per contrasto sullo sfondo scuro */
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; /* font leggibile */
    line-height: 1.5; /* interlinea confortevole */
  } /* fine body */

  /* ====== Header / Topbar (chiaro ma trasparente) ====== */
  .topbar{ 
    display: flex; /* layout flessibile orizzontale */
    align-items: center; /* centra verticalmente gli elementi */
    justify-content: space-between; /* separa brand e blocco nav */
    gap: 1rem; /* spazio orizzontale fra brand e menu */
    padding: .75rem 1rem; /* spaziatura interna */
    background: linear-gradient(to bottom, rgba(255,255,255,.45), rgba(255,255,255,.20)); /* gradiente chiaro ma non coprente (si vede lo sfondo) */
    color: #111; /* testo scuro per leggibilità */
    border-bottom: 1px solid rgba(0,0,0,.10); /* sottile bordo inferiore */
    position: sticky; /* rimane in alto durante lo scroll */
    top: 0; /* ancorato al top */
    z-index: 1000; /* sopra al resto del contenuto */
    backdrop-filter: blur(6px) saturate(160%); /* lieve sfocatura del contenuto retrostante */
  } /* fine topbar */

  .brand{ 
    display: inline-flex; /* layout inline flessibile per logo */
    align-items: center; /* allinea verticalmente */
    gap: .75rem; /* spazio eventuale (qui solo logo) */
    text-decoration: none; /* rimuove sottolineatura */
    color: inherit; /* eredita il colore scuro della topbar */
  } /* fine brand */

  .logo{ 
    width: 110px; /* larghezza del logo */
    height: 110px; /* altezza del logo */
    object-fit: contain; /* adatta mantenendo proporzioni (ottimo per SVG) */
    border-radius: 0; /* nessun arrotondamento: logo NON rotondo */
    /* background-color: #fff; */ /* se vuoi evitare trasparenze sullo sfondo, togli il commento a questa riga */
    padding: 6px; /* padding contenuto attorno al logo */
    /* border: 1px solid rgba(0,0,0,.08); */ /* bordino leggero opzionale */
    /* box-shadow: 0 2px 6px rgba(0,0,0,.06); */ /* ombra delicata opzionale */
  } /* fine logo */

  .navgroup{ 
    display: flex; /* contiene i due menu (principale + admin) */
    align-items: center; /* allinea verticalmente */
    gap: .75rem; /* spazio tra i menu */
  } /* fine navgroup */

  /* ====== Nav principale (Home / Songs) e nav admin ====== */
  .mainnav ul, .navbar ul{ 
    display: flex; /* lista orizzontale */
    list-style: none; /* rimuove pallini */
    margin: 0; /* rimuove margini default */
    padding: 0; /* rimuove padding default */
    gap: .75rem; /* spazio tra voci */
  } /* fine liste nav */

  .mainnav a, .navbar a{ 
    color: #111; /* link scuro coerente con header chiaro */
    text-decoration: none; /* rimuove sottolineatura */
    padding: .35rem .65rem; /* area cliccabile confortevole */
    border: 1px solid rgba(0,0,0,.12); /* bordo leggero */
    border-radius: 999px; /* pill shape */
    transition: transform .1s ease, background-color .2s ease, border-color .2s ease; /* animazioni fluide */
    background: rgba(255,255,255,.35); /* fondo semi-trasparente per far intravedere lo sfondo */
  } /* fine stile link nav */

  .mainnav a:hover, .navbar a:hover{ 
    background-color: rgba(0,0,0,.06); /* hover chiaro visibile */
    border-color: rgba(0,0,0,.18); /* bordo un po' più marcato in hover */
    transform: translateY(-1px); /* leggero sollevamento al passaggio */
  } /* fine hover link nav */

  /* ====== Intro e Container 50% centrati ====== */
  .intro, .container{ 
    width: 50%; /* occupano il 50% della larghezza pagina */
    max-width: 1000px; /* limite massimo per leggibilità */
    margin: 2rem  auto ; /* centrati orizzontalmente con margini verticali */
    padding: 4rem 6rem; /* spazio interno */
    background: rgba(0,0,0,0.35); /* pannello scuro semi-trasparente per contrasto sullo sfondo */
    border-radius: 12px; /* angoli arrotondati */
    border: 1px solid rgba(255,255,255,.15); /* bordo tenue */
    text-align: left; /* testo allineato a sinistra di default */
  } /* fine intro/container */

  .intro > h1, .container > h1{ 
    text-align: center; /* titoli centrati come richiesto */
    margin-top: 1rem; /* piccolo margine superiore */
    margin-bottom: 1rem; /* margine inferiore */

  } /* fine stile titoli */

  /* ====== Griglia e card ====== */
  .grid{ 
    display: grid; /* layout a griglia */
    grid-template-columns: 1fr; /* una colonna per focalizzarsi sul testo/video nella colonna 50% */
    gap: 1rem; /* spazio tra card */
  } /* fine grid */

  .card{ 
    background: rgba(0,0,0,0.65); /* sfondo semitrasparente della card */
    border: 1px solid #444; /* bordo card */
    border-radius: 12px; /* angoli arrotondati */
    padding: 1rem; /* spazio interno card */
    box-shadow: 0 1px 4px rgba(0,0,0,.5); /* ombra card */
  } /* fine card */

  .ratio-16x9{ 
    position: relative; /* contesto posizionamento per iframe */
    width: 100%; /* piena larghezza disponibile */
    padding-top: 56.25%; /* mantiene rapporto 16:9 (9/16 = 0.5625) */
  } /* fine ratio-16x9 */

  .ratio-16x9 iframe{ 
    position: absolute; /* posizionamento assoluto dentro wrapper */
    inset: 0; /* allinea ai bordi del wrapper */
    width: 100%; /* iframe responsivo */
    height: 100%; /* altezza responsiva */
    border: 0; /* rimuove bordo default */
    border-radius: 10px; /* angoli arrotondati player */
  } /* fine iframe responsivo */

  /* ====== Responsività ====== */
  @media (max-width: 1024px){ 
    .intro, .container{ width: 90%; } /* su tablet/mobile allarga a 90% per usabilità */
  } /* fine media query */

</style> <!-- fine stile index con nonce -->