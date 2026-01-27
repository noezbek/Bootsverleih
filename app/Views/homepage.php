<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        window.APP = {
            baseUrl: "<?= rtrim(base_url(), '/') ?>"
            csrf: document.querySelector('meta[name="csrf-token"]').content
        };
    </script>

    <title>Yachthafen Plau - Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('styles/home.css') ?>">
</head>
<body data-page="home">

<header>
    <h1>Yachthafen Plau am See</h1>
    <?= base_url() ?>

    <p class="subtitle">Verwaltungssystem</p>
</header>

<div class="dashboard-grid">
    <a href="<?= base_url('liegeplaetze') ?>" class="dashboard-item">
        <h2>Liegeplätze</h2>
        <p>Verwaltung und Reservierung von Bootsliegeplätzen</p>
    </a>

    <a href="<?= base_url('bootsverleih') ?>" class="dashboard-item">
        <h2>Bootsverleih</h2>
        <p>Vermietung und Buchung von Booten</p>
    </a>

    <a href="<?= base_url('zahlungen') ?>" class="dashboard-item">
        <h2>Zahlungen</h2>
        <p>Übersicht und Verwaltung aller Zahlungen</p>
    </a>
</div>

<div class="dashboard-grid">
    <a href="<?= base_url('support') ?>" class="dashboard-item">
        <h2>Support</h2>
        <p>Kontakt zum Support-Team für Hilfe und Anfragen</p>
    </a>

    <a href="<?= base_url('einstellungen') ?>" class="dashboard-item">
        <h2>Einstellungen</h2>
        <p>Persönliche Einstellungen und Anzeigeoptionen</p>
    </a>

    <form method="post" action="<?= base_url('logout') ?>" id="logout-form">
        <?= csrf_field() ?>
        <button type="submit" class="dashboard-item logout-item">
            <h2>Ausloggen</h2>
            <p>Sicher vom System abmelden</p>
        </button>
    </form>


</div>

<div class="dashboard-grid single-item">
    <a href="<?= base_url('kundenverwaltung') ?>" class="dashboard-item">
        <h2>Kundenverwaltung</h2>
        <p>Kundenkonten und Stammdaten verwalten</p>
    </a>
</div>


<script type="module" src="<?= base_url('js/app.js') ?>"></script>


</body>
</html>
