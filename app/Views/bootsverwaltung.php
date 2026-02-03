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

    <title>Bootsverwaltung - Yachthafen Plau</title>
    <link rel="stylesheet" href="<?= base_url('styles/bootsverwaltung.css') ?>">
</head>

<body data-page="bootsverwaltung">

<div class="container">

    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <div class="header-left">
            <h1>Bootsverwaltung</h1>
            <p class="subtitle">Verwaltung aller Boote, Preise und Verfügbarkeit</p>
        </div>
        <button class="btn btn-primary" id="btnAddBoat">+ Neues Boot</button>
    </header>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Suche nach Name, Typ, ID...">
        <button class="btn btn-primary" id="btnSearch">Suchen</button>
    </div>

    <div class="data-table">
        <table id="boatsTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Typ</th>
                <th>Kapazität</th>
                <th>Preis/Tag</th>
                <th>Kaution</th>
                <th>Verfügbarkeit</th>
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
            <h2>Boot Details</h2>
            <span class="close" data-close="viewModal">&times;</span>
        </div>

        <div class="info-section">
            <h3>Boot-Informationen</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">ID</span>
                    <span class="info-value" id="view-boatId"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Name</span>
                    <span class="info-value" id="view-name"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Bootstyp</span>
                    <span class="info-value" id="view-type"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kapazität</span>
                    <span class="info-value" id="view-capacity"></span>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>Abmessungen</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Länge</span>
                    <span class="info-value" id="view-length"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Breite</span>
                    <span class="info-value" id="view-width"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tiefgang</span>
                    <span class="info-value" id="view-depth"></span>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>Preise</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Preis pro Tag</span>
                    <span class="info-value" id="view-price"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kaution</span>
                    <span class="info-value" id="view-deposit"></span>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>Features</h3>
            <div class="features-list" id="view-features">
                <div class="empty-state">Keine Features zugewiesen</div>
            </div>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2 id="editModalTitle">Boot bearbeiten</h2>
            <span class="close" data-close="editModal">&times;</span>
        </div>

        <input type="hidden" id="edit-boatId">

        <div class="form-group">
            <label for="edit-name">Name / Beschreibung</label>
            <input type="text" id="edit-name" placeholder="Bootsname">
        </div>

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-type">Bootstyp</label>
                <select id="edit-type">
                    <option value="">— Wählen —</option>
                    <option value="1">Segelboot</option>
                    <option value="2">Motorboot</option>
                    <option value="3">Kajak</option>
                    <option value="4">Ruderboot</option>
                    <option value="5">Katamaran</option>
                </select>
            </div>
            <div class="form-group">
                <label for="edit-capacity">Kapazität (Personen)</label>
                <input type="number" id="edit-capacity" placeholder="0">
            </div>
        </div>

        <div class="info-grid three-col">
            <div class="form-group">
                <label for="edit-length">Länge (m)</label>
                <input type="number" step="0.01" id="edit-length" placeholder="0.00">
            </div>
            <div class="form-group">
                <label for="edit-width">Breite (m)</label>
                <input type="number" step="0.01" id="edit-width" placeholder="0.00">
            </div>
            <div class="form-group">
                <label for="edit-depth">Tiefgang (m)</label>
                <input type="number" step="0.01" id="edit-depth" placeholder="0.00">
            </div>
        </div>

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-price">Preis pro Tag (EUR)</label>
                <input type="number" step="0.01" id="edit-price" placeholder="0.00">
            </div>
            <div class="form-group">
                <label for="edit-deposit">Kaution (EUR)</label>
                <input type="number" step="0.01" id="edit-deposit" placeholder="0.00">
            </div>
        </div>

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-availability">Verfügbarkeit</label>
                <select id="edit-availability">
                    <option value="1">Verfügbar</option>
                    <option value="2">Reserviert</option>
                    <option value="3">In Wartung</option>
                    <option value="4">Außer Betrieb</option>
                </select>
            </div>
            <div class="form-group">
                <label for="edit-status">Status</label>
                <select id="edit-status">
                    <option value="1">Aktiv</option>
                    <option value="0">Inaktiv</option>
                </select>
            </div>
        </div>

        <div class="modal-actions">
            <button class="btn btn-primary" id="btnSaveBoat" type="button">Speichern</button>
            <button class="btn btn-cancel" id="btnCancelEdit" type="button">Abbrechen</button>
        </div>
    </div>
</div>

<script type="module" src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
