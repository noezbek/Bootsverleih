<h2>Registrieren</h2>

<form method="post" action="<?= base_url('register') ?>">
    <?= csrf_field() ?>

    <label>
        Benutzername
        <input type="text" name="username" required>
    </label>

    <label>
        Passwort
        <input type="password" name="password" required>
    </label>

    <label>
        Passwort wiederholen
        <input type="password" name="password_repeat" required>
    </label>

    <button type="submit">Registrieren</button>
</form>

<p class="switch-text">
    Schon registriert?
    <button type="button" data-switch="login">Zurück zum Login</button>
</p>
