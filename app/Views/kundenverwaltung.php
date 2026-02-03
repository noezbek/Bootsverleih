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

    <title>Kundenverwaltung - Yachthafen Plau</title>
    <link rel="stylesheet" href="<?= base_url('styles/kundenverwaltung.css') ?>">
</head>

<body data-page="kundenverwaltung">

<div class="container">

    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <div class="header-left">
            <h1>Kundenverwaltung</h1>
            <p class="subtitle">Verwaltung aller Kundenkonten und Stammdaten</p>
        </div>
        <button class="btn btn-primary" id="btnAddCustomer">+ Neuer Kunde</button>
    </header>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Suche nach Name, E-Mail, ID...">
        <button class="btn btn-primary" id="btnSearch">Suchen</button>
    </div>

    <div class="customers-table">
        <table id="customersTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>E-Mail</th>
                <th>Telefon</th>
                <th>Status</th>
                <th>Geburtsdatum</th>
                <th>Aktionen</th>
            </tr>
            </thead>

            <tbody>
            <!-- wird durch JS gefüllt -->
            </tbody>
        </table>
    </div>
</div>

<!-- VIEW MODAL -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Kundendetails</h2>
            <span class="close" data-close="viewModal">&times;</span>
        </div>

        <div class="info-section">
            <h3>Persönliche Informationen</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Kunden-ID</span>
                    <span class="info-value" id="view-customerId"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Name</span>
                    <span class="info-value" id="view-name"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">E-Mail</span>
                    <span class="info-value" id="view-email"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Telefon</span>
                    <span class="info-value" id="view-phone"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Adresse</span>
                    <span class="info-value" id="view-address"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Geburtsdatum</span>
                    <span class="info-value" id="view-geburtsdatum"></span>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>Zahlungsinformationen</h3>
            <div id="view-payments">
                <div class="history-item">Noch keine Zahlungsdaten</div>
            </div>
        </div>

        <div class="info-section">
            <h3>Bestellhistorie</h3>
            <div id="view-orders">
                <div class="history-item">Noch keine Bestellhistorie</div>
            </div>
        </div>
    </div>
</div>


<!-- EDIT MODAL (wir lassen erstmal die Inputs wie bei dir, Save kommt per JS) -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Kunde bearbeiten</h2>
            <span class="close" data-close="editModal">&times;</span>
        </div>

        <input type="hidden" id="edit-customerId">

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-firstName">Vorname</label>
                <input type="text" id="edit-firstName" placeholder="Vorname">
            </div>
            <div class="form-group">
                <label for="edit-lastName">Nachname</label>
                <input type="text" id="edit-lastName" placeholder="Nachname">
            </div>
        </div>

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-email">E-Mail</label>
                <input type="email" id="edit-email" placeholder="E-Mail">
            </div>
            <div class="form-group">
                <label for="edit-phone">Telefon</label>
                <input type="tel" id="edit-phone" placeholder="Telefon">
            </div>
        </div>

        <div class="form-group">
            <label for="edit-address">Straße</label>
            <input type="text" id="edit-address" placeholder="Straße">
        </div>

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-city">Stadt</label>
                <input type="text" id="edit-city" placeholder="Stadt">
            </div>
            <div class="form-group">
                <label for="edit-zip">PLZ</label>
                <input type="text" id="edit-zip" placeholder="PLZ">
            </div>
        </div>

        <div class="form-group">
            <label for="edit-geburtsdatum">Geburtsdatum</label>
            <input type="date" id="edit-geburtsdatum">
        </div>

        <div class="form-group">
            <label for="edit-status">Status</label>
            <select id="edit-status">
                <option value="">—</option>
                <option value="1">Aktiv</option>
                <option value="0">Inaktiv</option>
            </select>
        </div>

        <div class="modal-actions">
            <button class="btn btn-primary" id="btnSaveCustomer" type="button">Speichern</button>
            <button class="btn btn-cancel" id="btnCancelEdit" type="button">Abbrechen</button>
        </div>
    </div>
</div>

<script type="module" src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
