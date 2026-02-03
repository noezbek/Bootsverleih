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

    <title>Liegeplatzverwaltung - Yachthafen Plau</title>
    <link rel="stylesheet" href="<?= base_url('styles/liegeplatzverwaltung.css') ?>">
</head>

<body data-page="liegeplatzverwaltung">

<div class="container">

    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <div class="header-left">
            <h1>Liegeplatzverwaltung</h1>
            <p class="subtitle">Verwaltung aller Liegeplätze, Reservierungen und Preise</p>
        </div>
    </header>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Suche nach Bezeichnung, ID...">
        <button class="btn btn-primary" id="btnSearch">Suchen</button>
    </div>

    <div class="data-table">
        <table id="berthsTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Bezeichnung</th>
                <th>Beschreibung</th>
                <th>Kapazität</th>
                <th>Preis/Tag</th>
                <th>Reservierungen</th>
                <th>Status</th>
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
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>Liegeplatz Details</h2>
            <span class="close" data-close="viewModal">&times;</span>
        </div>

        <div class="info-section">
            <h3>Liegeplatz-Informationen</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">ID</span>
                    <span class="info-value" id="view-berthId"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Bezeichnung</span>
                    <span class="info-value" id="view-bezeichnung"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Beschreibung</span>
                    <span class="info-value" id="view-beschreibung"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kapazität</span>
                    <span class="info-value" id="view-kapazitaet"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Preis pro Tag</span>
                    <span class="info-value" id="view-preis"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value" id="view-status"></span>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>Reservierungen</h3>
            <div class="reservations-list" id="view-reservations">
                <div class="empty-state">Keine Reservierungen vorhanden</div>
            </div>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="editModalTitle">Liegeplatz bearbeiten</h2>
            <span class="close" data-close="editModal">&times;</span>
        </div>

        <input type="hidden" id="edit-berthId">

        <div class="form-group">
            <label for="edit-bezeichnung">Bezeichnung</label>
            <input type="text" id="edit-bezeichnung" placeholder="z.B. A-01">
        </div>

        <div class="form-group">
            <label for="edit-beschreibung">Beschreibung</label>
            <textarea id="edit-beschreibung" placeholder="Beschreibung des Liegeplatzes"></textarea>
        </div>

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-kapazitaet">Kapazität (Bootsgröße)</label>
                <input type="number" id="edit-kapazitaet" placeholder="Max. Bootslänge in m">
            </div>
            <div class="form-group">
                <label for="edit-preis">Preis pro Tag (EUR)</label>
                <input type="number" step="0.01" id="edit-preis" placeholder="0.00">
            </div>
        </div>

        <div class="form-group">
            <label for="edit-status">Status</label>
            <select id="edit-status">
                <option value="1">Aktiv</option>
                <option value="0">Inaktiv</option>
            </select>
        </div>

        <div class="modal-actions">
            <button class="btn btn-primary" id="btnSaveBerth" type="button">Speichern</button>
            <button class="btn btn-cancel" id="btnCancelEdit" type="button">Abbrechen</button>
        </div>
    </div>
</div>

<!-- RESERVATION EDIT MODAL -->
<div id="reservationEditModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Reservierung bearbeiten</h2>
            <span class="close" data-close="reservationEditModal">&times;</span>
        </div>

        <input type="hidden" id="reservation-id">

        <div class="info-grid">
            <div class="form-group">
                <label for="reservation-start">Startdatum</label>
                <input type="date" id="reservation-start">
            </div>
            <div class="form-group">
                <label for="reservation-end">Enddatum</label>
                <input type="date" id="reservation-end">
            </div>
        </div>

        <div class="form-group">
            <label for="reservation-status">Status</label>
            <select id="reservation-status">
                <option value="1">Angefragt</option>
                <option value="2">Reserviert</option>
                <option value="3">Storniert</option>
            </select>
        </div>

        <div class="modal-actions">
            <button class="btn btn-primary" id="btnSaveReservation" type="button">Speichern</button>
            <button class="btn btn-danger" id="btnDeleteReservation" type="button">Löschen</button>
            <button class="btn btn-cancel" id="btnCancelReservation" type="button">Abbrechen</button>
        </div>
    </div>
</div>

<script type="module" src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
