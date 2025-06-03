<h2>Il tuo carrello</h2>

<table>
    <thead>
        <tr><th>Prodotto</th><th>Prezzo</th><th>Quantità</th><th>Totale</th><th>Azione</th></tr>
    </thead>
    <tbody>
    <?php foreach ($cartItems as $id => $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['prodotto']->titolo) ?></td>
            <td><?= number_format($item['prodotto']->prezzo, 2) ?> €</td>
            <td><?= htmlspecialchars($item['quantita']) ?></td>
            <td><?= number_format($item['prodotto']->prezzo * $item['quantita'], 2) ?> €</td>
            <td>
                <form method="POST" action="/carrello/rimuovi/<?= $id ?>">
                    <button type="submit">Rimuovi</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h3>Totale: <?= number_format($total, 2) ?> €</h3>

<a href="/checkout">Procedi al pagamento</a>
