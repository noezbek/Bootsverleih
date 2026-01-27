<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        window.APP = {
            baseUrl: "<?= rtrim(base_url(), '/') ?>"
        };
    </script>


    <title>Yachthafen Plau - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        body.dark-mode {
            background: #1a1a1a;
            color: #e0e0e0;
        }

        header {
            text-align: center;
            margin-bottom: 50px;
        }

        h1 {
            font-size: 48px;
            color: #333;
            margin-bottom: 10px;
        }

        body.dark-mode h1 {
            color: #e0e0e0;
        }

        .subtitle {
            font-size: 16px;
            color: #666;
        }

        body.dark-mode .subtitle {
            color: #999;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 900px;
            width: 100%;
            margin-bottom: 30px;
        }

        .dashboard-grid.single-item {
            grid-template-columns: 1fr;
            max-width: 300px;
        }

        .dashboard-item {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        body.dark-mode .dashboard-item {
            background: #2d2d2d;
            border-color: #444;
            color: #e0e0e0;
        }

        .dashboard-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-color: #0b3c5d;
        }

        body.dark-mode .dashboard-item:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            border-color: #64b5f6;
        }

        .dashboard-item h2 {
            font-size: 20px;
            color: #0b3c5d;
            margin-bottom: 10px;
        }

        body.dark-mode .dashboard-item h2 {
            color: #64b5f6;
        }

        .dashboard-item p {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }

        body.dark-mode .dashboard-item p {
            color: #999;
        }

        .logout-item {
            border-color: #d32f2f;
        }

        .logout-item h2 {
            color: #d32f2f;
        }

        body.dark-mode .logout-item h2 {
            color: #ff6b6b;
        }

        .logout-item:hover {
            border-color: #d32f2f;
            background: #fff5f5;
        }

        body.dark-mode .logout-item {
            border-color: #c62828;
        }

        body.dark-mode .logout-item:hover {
            background: #3d2020;
            border-color: #ff6b6b;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .dashboard-grid.single-item {
                max-width: 100%;
            }
        }

        @media (max-width: 600px) {
            .dashboard-grid,
            .dashboard-grid.single-item {
                grid-template-columns: 1fr;
            }
        }
    </style>
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

    <div class="dashboard-item logout-item" data-logout-button>
        <h2>Ausloggen</h2>
        <p>Sicher vom System abmelden</p>
    </div>

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
