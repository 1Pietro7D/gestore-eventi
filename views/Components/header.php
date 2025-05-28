<nav>
    <ul class="navbar">
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="/logout">Logout</a></li>     
        <?php else: ?>
            <li><a href="/login">login</a></li>
            <li><a href="/register">Registrati</a></li>
        <?php endif; ?> 

        <li><a href="/">home</a></li>

        <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['ruolo'], ['admin', 'superadmin'])): ?>
            <li><a href="/eventi">eventi</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['ruolo'] === 'superadmin'): ?>
            <li><a href="/utenti">utenti</a></li>
            <li><a href="/categorie">categorie</a></li>
        <?php endif; ?>
    </ul>
    
</nav>

<style>
    .navbar{
        display: flex;
        gap: 2rem;
    }
</style>

