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
    <title>Liegeplätze - Yachthafen Plau</title>

    <link rel="stylesheet" href="<?= base_url('styles/liegeplaetze.css') ?>">
</head>
<body data-page="liegeplaetze">

<div class="page-layout">
    <!-- Main Map Area -->
    <div class="map-container">
        <!-- Header Overlay -->
        <div class="header-overlay">
            <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>
            <header>
                <h1>Liegeplätze</h1>
                <p class="subtitle">Ziehen Sie ein Boot auf einen Liegeplatz, um ihn zu reservieren</p>
            </header>
        </div>

        <!-- Map Viewport -->
        <div class="map-viewport" id="mapViewport">
            <div class="map-content" id="mapContent">
                <img src="<?= base_url('Liegeplätze.png') ?>" alt="Marina Karte" class="marina-map" id="marinaMap">
                <div class="berths-overlay" id="berthsOverlay"></div>
            </div>
        </div>
    </div>

    <!-- Sidebar with boats -->
    <aside class="boats-sidebar" id="boatsSidebar">
        <h2>Meine Boote</h2>
        <p class="sidebar-hint">Ziehen Sie ein Boot auf die Karte</p>
        <div class="boats-list" id="boatsList"></div>
    </aside>
</div>

<!-- Reservation Modal -->
<div id="reservationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Liegeplatz reservieren</h2>
            <span class="close" data-close="reservationModal">&times;</span>
        </div>

        <form id="reservationForm">
            <input type="hidden" id="reservation-berth-id">
            <input type="hidden" id="reservation-boat-id">

            <div class="reservation-info">
                <div class="info-row">
                    <span class="info-label">Liegeplatz:</span>
                    <span class="info-value" id="info-berth-name">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kapazität:</span>
                    <span class="info-value" id="info-capacity">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Boot:</span>
                    <span class="info-value" id="info-boat-name">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Preis pro Tag:</span>
                    <span class="info-value price" id="info-price-per-day">-</span>
                </div>
            </div>

            <div class="form-group">
                <label for="reservation-start-date">Startdatum</label>
                <input type="date" id="reservation-start-date" required>
            </div>

            <div class="form-group">
                <label for="reservation-end-date">Enddatum</label>
                <input type="date" id="reservation-end-date" required>
            </div>

            <div class="cost-summary">
                <div class="cost-line">
                    <span>Dauer:</span>
                    <span id="cost-days">-</span>
                </div>
                <div class="cost-total">
                    <span>Gesamtkosten:</span>
                    <span id="cost-total">0,00 €</span>
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Reservieren</button>
                <button type="button" class="btn btn-secondary" data-close="reservationModal">Abbrechen</button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
