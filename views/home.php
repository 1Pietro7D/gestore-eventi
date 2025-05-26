<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .evento-card {
            padding: 2rem;
            border: 1px solid black;
            border-radius: .5rem;
            width: 300px;
        }
        .pagination {
            margin-top: 2rem;
        }
        .pagination a {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/Components/header.php'; ?>
    
    <h1>Home</h1>
    <h2>Eventi</h2>

    <?php if (empty($paginazione['data'])): ?>
        <p>Nessun evento disponibile.</p>
    <?php else: ?>
        <div class="container">
            <?php foreach ($paginazione['data'] as $evento): ?>
                <div class="evento-card">
                    <h3><?= htmlspecialchars($evento->titolo) ?></h3>
                    
                    <div>
                        <p><strong>Data evento:</strong></p>
                        <p><?= htmlspecialchars($evento->data_evento) ?></p>
                    </div>

                    <div>
                        <p><strong>Categoria:</strong></p>
                        <p><?= htmlspecialchars($evento->categoria->nome) ?></p>
                    </div>

                    <p>Iscritti: <?= $evento->numeroIscritti() ?> / <?= $evento->massimo_partecipanti ?></p>

                    <div>
                        <?php if ($evento->img_path): ?>
                            <img src="/public<?= htmlspecialchars($evento->img_path) ?>" alt="Immagine Evento" width="100">
                        <?php else: ?>
                            <p>Nessuna immagine</p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <?php if ($evento->video_path): ?>
                            <a href="/public<?= htmlspecialchars($evento->video_path) ?>" target="_blank">Guarda Video</a>
                        <?php else: ?>
                            <p>Nessun video</p>
                        <?php endif; ?>
                    </div>

                    <a href="/eventi/show/<?= $evento->id ?>">Visualizza</a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Navigazione -->
        <div class="pagination">
            <p>
                Pagina <?= $paginazione['current_page'] ?> di <?= $paginazione['last_page'] ?> <br>
                Risultati da <?= $paginazione['from'] ?> a <?= $paginazione['to'] ?> su <?= $paginazione['total'] ?>
            </p>

            <nav>
                <a href="<?= $paginazione['first_page_url'] ?>">prima pagina</a>
                <?php if ($paginazione['prev_page_url']): ?>
                    <a href="<?= $paginazione['prev_page_url'] ?>">Precedente</a>
                <?php endif; ?>

                <?php if ($paginazione['next_page_url']): ?>
                    <a href="<?= $paginazione['next_page_url'] ?>">Successiva</a>
                <?php endif; ?>
                <a href="<?= $paginazione['last_page_url'] ?>">ultima pagina</a>
            </nav>
        </div>
    <?php endif; ?>
</body>
</html>
