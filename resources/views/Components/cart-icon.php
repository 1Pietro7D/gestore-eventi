<?php
$cartCount = Cart::count();
?>
    <a href="/carrello" class="cart-link">🛒
        <?php if ($cartCount > 0): ?>
            <span class="cart-badge"><?= $cartCount ?></span>
        <?php endif; ?>
    </a>

    <style nonce="<?= htmlspecialchars($GLOBALS['CSP_NONCE']) ?>">
.cart-link {
    position: relative;
    text-decoration: none;
}
.cart-badge {
    position: absolute;
    top: -0.5rem;
    right: -0.5rem;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 0 6px;
    font-size: 0.7rem;
}
</style>