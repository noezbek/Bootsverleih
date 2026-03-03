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
    <title>Bootsverleih - Yachthafen Plau</title>

    <link rel="stylesheet" href="<?= base_url('styles/bootsverleih.css') ?>">
</head>
<body data-page="bootsverleih">

<div class="container">
    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <h1>Bootsverleih</h1>
        <p class="subtitle">Wählen Sie Ihr perfektes Boot für Ihr nächstes Abenteuer</p>
    </header>

    <div class="filters">
        <div class="filter-group">
            <label for="sortBy">Sortieren nach</label>
            <select id="sortBy">
                <option value="name">Name</option>
                <option value="price-low">Preis (niedrig-hoch)</option>
                <option value="price-high">Preis (hoch-niedrig)</option>
                <option value="capacity">Kapazität</option>
                <option value="type">Bootstyp</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="filterType">Bootstyp</label>
            <select id="filterType">
                <option value="all">Alle</option>
                <option value="segelboot">Segelboot</option>
                <option value="motorboot">Motorboot</option>
                <option value="kajak">Kajak</option>
                <option value="kanu">Kanu</option>
                <option value="sup">SUP</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="filterCapacity">Min. Kapazität</label>
            <select id="filterCapacity">
                <option value="0">Alle</option>
                <option value="2">2+ Personen</option>
                <option value="4">4+ Personen</option>
                <option value="6">6+ Personen</option>
            </select>
        </div>
    </div>

    <div class="boats-grid" id="boatsGrid"></div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-boat-name">Boot buchen</h2>
            <span class="close" data-close="bookingModal">&times;</span>
        </div>

        <form id="bookingForm">
            <input type="hidden" id="booking-boat-id">

            <div class="form-group">
                <label for="booking-start-date">Startdatum</label>
                <input type="date" id="booking-start-date" required>
            </div>

            <div class="form-group">
                <label for="booking-end-date">Enddatum</label>
                <input type="date" id="booking-end-date" name="end_date" required>
            </div>

<!--            <div class="form-group">-->
<!--                <label for="booking-name">Ihr Name</label>-->
<!--                <input type="text" id="booking-name" required>-->
<!--            </div>-->
<!---->
<!--            <div class="form-group">-->
<!--                <label for="booking-email">E-Mail</label>-->
<!--                <input type="email" id="booking-email" required>-->
<!--            </div>-->
<!---->
<!--            <div class="form-group">-->
<!--                <label for="booking-phone">Telefon</label>-->
<!--                <input type="tel" id="booking-phone" required>-->
<!--            </div>-->

            <div class="cost-summary">
                <div class="cost-line">
                    <span>Tagespreis:</span>
                    <span id="cost-daily">0,00 €</span>
                </div>
                <div class="cost-line">
                    <span>Dauer:</span>
                    <span id="cost-duration">-</span>
                </div>
                <div class="cost-line">
                    <span>Kaution:</span>
                    <span id="cost-deposit">0,00 €</span>
                </div>
                <div class="cost-total">
                    <span>Gesamtpreis:</span>
                    <span id="cost-total">0,00 €</span>
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Jetzt buchen</button>
                <button type="button" class="btn btn-secondary" data-close="bookingModal">Abbrechen</button>
            </div>
        </form>
    </div>
</div>
<script type="module" src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
