<h2>Login</h2>

<form method="post" action="<?= base_url('login') ?>">
    <?= csrf_field() ?>

    <label>
        Benutzername
        <input type="text" name="username" required>
    </label>

    <label>
        Passwort
        <input type="password" name="password" required>
    </label>

    <button type="submit">Einloggen</button>
</form>

<p class="switch-text">
    Noch nicht registriert?
    <button type="button" data-switch="register">Jetzt registrieren</button>
</p>
