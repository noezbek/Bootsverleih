<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Authentifizierung</title>

    <link rel="stylesheet" href="<?= base_url('styles/auth.css') ?>">
</head>
<body>

<div class="auth-wrapper">

    <div class="auth-switch">
        <button id="showLogin" class="active">Login</button>
        <button id="showRegister">Registrieren</button>
    </div>

    <div class="auth-box">

        <div id="loginBox">
            <?= view('login_form') ?>
        </div>

        <div id="registerBox" class="hidden">
            <?= view('register_form') ?>
        </div>

    </div>

</div>

<script src="<?= base_url('js/auth.js') ?>"></script>
</body>
</html>
