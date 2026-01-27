<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red;">
        <?= esc(session()->getFlashdata('error')) ?>
    </p>
<?php endif; ?>

<form method="post" action="<?= base_url('login') ?>">
    <?= csrf_field() ?>

    <label>
        Username<br>
        <input type="text" name="username" required>
    </label>
    <br><br>

    <label>
        Passwort<br>
        <input type="password" name="password" required>
    </label>
    <br><br>

    <button type="submit">Login</button>
</form>

<hr>

<h2>Registrierung (Test)</h2>

<form method="post" action="<?= base_url('register') ?>">
    <?= csrf_field() ?>

    <label>
        Username<br>
        <input type="text" name="username" required>
    </label>
    <br><br>

    <label>
        Passwort<br>
        <input type="password" name="password" required>
    </label>
    <br><br>

    <button type="submit">Registrieren</button>
</form>

</body>
</html>
