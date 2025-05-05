<h1>Registrazione</h1>
<form method="POST" action="/mini3/register">
    <label>Nome: <input type="text" name="nome" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Ruolo: 
        <select name="ruolo">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </label><br>
    <button>Registrati</button>
</form>
