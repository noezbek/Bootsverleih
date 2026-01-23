<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <script>
        window.APP = {
            baseUrl: "<?= rtrim(base_url(), '/') ?>",
            csrf: document.querySelector('meta[name="csrf-token"]').content
        };
    </script>

    <title>Zahlungen - Yachthafen Plau</title>

    <link rel="stylesheet" href="<?= base_url('styles/zahlungen.css') ?>">
</head>
<body data-page="zahlungen">

<div class="container">
    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <h1>Zahlungen</h1>
        <p class="subtitle">Übersicht Ihrer aktiven Zahlungen und Abonnements</p>
    </header>

    <!-- Payment Cards -->
    <div id="paymentsContainer"></div>

    <!-- Payment History -->
    <div class="payment-history">
        <h2>Zahlungshistorie</h2>
        <table>
            <thead>
            <tr>
                <th>Datum</th>
                <th>Beschreibung</th>
                <th>Betrag</th>
                <th>Status</th>
                <th>Rechnung</th>
            </tr>
            </thead>
            <tbody id="paymentHistoryBody"></tbody>
        </table>
    </div>
</div>

<script type="module" src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
