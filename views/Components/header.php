<nav cache="false">
    <ul class="navbar">
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="/logout">Logout</a></li>     
        <?php else: ?>
            <li>
                <a href="/login">login</a>
            </li>
            <li>
                <a href="/register">Registrati</a>
            </li>
        <?php endif; ?> 
        <li>
            <a href="/eventi">eventi</a>
        </li>
        <li>
            <a href="/utenti">utenti</a>
        </li>
    </ul>
</nav>

<style>
    .navbar{
        display: flex;
        gap: 2rem;
    }
</style>

