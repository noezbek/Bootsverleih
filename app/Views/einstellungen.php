<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <script>
        window.APP = {
            baseUrl: "<?= rtrim(base_url(), '/') ?>",
            csrf: "<?= csrf_hash() ?>"
        };
    </script>
    <title>Einstellungen - Yachthafen Plau</title>

    <link rel="stylesheet" href="<?= base_url('styles/einstellungen.css') ?>">
</head>
<body data-page="einstellungen">

<div class="container">
    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <h1>Einstellungen</h1>
    </header>

    <div class="settings-section">
        <h2>Nutzereinstellungen</h2>

        <div class="setting-item">
            <div class="setting-info">
                <h3>Profilname</h3>
                <p>Ihr angezeigter Name im System</p>
            </div>
            <button class="edit-button" id="editProfileBtn">Bearbeiten</button>
        </div>

        <div class="setting-item">
            <div class="setting-info">
                <h3>E-Mail Benachrichtigungen</h3>
                <p>Erhalten Sie Updates per E-Mail</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="emailNotifications">
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <div class="settings-section">
        <h2>Darstellung</h2>

        <div class="setting-item">
            <div class="setting-info">
                <h3>Dark Mode</h3>
                <p>Zwischen hellem und dunklem Design wechseln</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="darkModeToggle">
                <span class="slider"></span>
            </label>
        </div>
    </div>
</div>

<script type="module" src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
