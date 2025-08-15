<style nonce="<?= $GLOBALS['CSP_NONCE'] ?>">
    .d-inline {
        display: inline;
    }
</style>
<?php require_once __DIR__.  ('/../Components/header.php'); ?>
<h1>Elenco Categorie</h1>

<a href="categorie/create"> Aggiungi nuova categoria</a>

<ul>
    <?php foreach ($categorie as $categoria): ?>
        <li>
            <?= htmlspecialchars($categoria->nome) ?>
            <a href="categorie/edit/<?=$categoria->id?>"> Modifica</a>
            <form action="categorie/delete/<?=  $categoria->id ?>" method="POST" class="d-inline">
                <button type="submit" onclick="return confirm('Sei sicuro di voler eliminare questa categoria?')">Elimina</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
