<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Authentifizierung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= base_url('styles/auth.css') ?>">

    <script>
        window.APP = {
            baseUrl: "<?= rtrim(base_url(), '/') ?>"
        };
    </script>
</head>
<body>

<div class="auth-box">

    <?php if (session()->getFlashdata('error')): ?>
        <div class="error">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div id="login-form" class="auth-form active">
        <?= view('login_form') ?>
    </div>

    <div id="register-form" class="auth-form">
        <?= view('register_form') ?>
    </div>

</div>

<script type="module" src="<?= base_url('js/auth.js') ?>"></script>
</body>
</html>
