<h2>Registrieren</h2>

<form method="post" action="<?= base_url('register') ?>">
    <?= csrf_field() ?>

    <label>
        Username
        <input type="text" name="username" required>
    </label>

    <label>
        Passwort
        <input type="password" name="password" required>
    </label>

    <label>
        Passwort wiederholen
        <input type="password" name="passwordRepeat" required>
    </label>

    <label class="agb">
        <input type="checkbox" name="agb" required>
        AGB akzeptieren
    </label>

    <button type="submit">Registrieren</button>

    <p class="switch-hint">
        Schon einen Account?
        <span data-switch="login">Zum Login</span>
    </p>
</form>
