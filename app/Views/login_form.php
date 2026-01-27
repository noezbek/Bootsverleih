<h2>Login</h2>

<?php if (session()->getFlashdata('error')): ?>
    <p class="error">
        <?= esc(session()->getFlashdata('error')) ?>
    </p>
<?php endif; ?>

<form method="post" action="<?= base_url('login') ?>">
    <?= csrf_field() ?>

    <label>
        Username
        <input type="text" name="username" required>
    </label>

    <label>
        Passwort
        <input type="password" name="password" required>
    </label>

    <button type="submit">Login</button>

    <p class="switch-hint">
        Noch nicht registriert?
        <span data-switch="register">Jetzt registrieren</span>
    </p>
</form>
