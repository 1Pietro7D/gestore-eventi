<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <style>
        .container{
            display: flex;
            gap: 1rem;
        }
        .evento-card{
            padding: 2rem;
            border: 1px solid black;
            border-radius: .5rem;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__.  ('/Components/header.php'); ?>
    <h1>Home</h1>
    <h2>Eventi</h2>
    <div class="container">
        <?php foreach ($eventi as $evento): ?>
            <div class="evento-card">
                <h3>
                    <?= htmlspecialchars($evento->titolo) ?>
                </h3>
                <div>
                    <p>Data evento</p>
                    <p><?= htmlspecialchars($evento->data_evento) ?></p>          
                </div>
                <div>
                    <?php if ($evento->img_path): ?>
                        <img src="/public<?= htmlspecialchars($evento->img_path) ?>" alt="Immagine Evento" width="100">
                    <?php else: ?>
                        Nessuna immagine
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ($evento->video_path): ?>
                        <a href="/public<?= htmlspecialchars($evento->video_path) ?>" target="_blank">Guarda Video</a>
                    <?php else: ?>
                        Nessun video
                    <?php endif; ?>
                </div>
                <a href="/eventi/show/<?= $evento->id ?>">Visualizza</a>
            </div>    
        <?php endforeach; ?>
    </div>
<!-- 
    <?php if (isset($_SESSION['user'])): ?>
        <p>Ciao <?= htmlspecialchars($_SESSION['user']['nome']) ?>!</p>
        <p>Sei autenticato come <strong><?= htmlspecialchars($_SESSION['user']['ruolo']) ?></strong></p>
        <a href="/logout">Logout</a> |
        <form method="POST" action="/delete" style="display:inline">
            <button type="submit">Cancella Registrazione</button>
        </form>
    <?php else: ?>
        <p>Non sei autenticato.</p>
        <a href="/login">Login</a> |
        <a href="/register">Registrati</a>
    <?php endif; ?> -->

</body>
</html>

