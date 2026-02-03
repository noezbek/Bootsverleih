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

    <title>Account Einstellungen - Yachthafen Plau</title>
    <link rel="stylesheet" href="<?= base_url('styles/accountEinstellungen.css') ?>">
</head>

<body data-page="accountEinstellungen">

<div class="container">
    <a href="<?= base_url('/einstellungen') ?>" class="back-link">← Zurück zu Einstellungen</a>

    <header>
        <h1>Account Einstellungen</h1>
        <p class="subtitle">Verwalten Sie Ihre persönlichen Daten und Kontoinformationen</p>
    </header>

    <div id="accountMessage" class="message" style="display: none;"></div>

    <!-- Personal Data Section -->
    <div class="settings-section">
        <h2>Persönliche Daten</h2>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Vorname</span>
                <span class="info-value" id="view-vorname">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">Nachname</span>
                <span class="info-value" id="view-nachname">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">E-Mail</span>
                <span class="info-value" id="view-email">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">Telefon</span>
                <span class="info-value" id="view-telefon">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">Strasse</span>
                <span class="info-value" id="view-strasse">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">PLZ</span>
                <span class="info-value" id="view-plz">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">Stadt</span>
                <span class="info-value" id="view-stadt">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">Geburtsdatum</span>
                <span class="info-value" id="view-geburtsdatum">-</span>
            </div>
        </div>

        <div class="button-group">
            <button class="btn btn-primary" id="btnEditPersonal">Bearbeiten</button>
        </div>
    </div>

    <!-- Account Credentials Section -->
    <div class="settings-section">
        <h2>Kontodaten</h2>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Benutzername</span>
                <span class="info-value" id="view-username">-</span>
            </div>
        </div>

        <div class="button-group">
            <button class="btn btn-secondary" id="btnChangeUsername">Benutzername ändern</button>
            <button class="btn btn-secondary" id="btnChangePassword">Passwort ändern</button>
        </div>
    </div>
</div>

<!-- Modal: Edit Personal Data -->
<div id="editPersonalModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Persönliche Daten bearbeiten</h2>
            <span class="close" data-close="editPersonalModal">&times;</span>
        </div>

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-vorname">Vorname</label>
                <input type="text" id="edit-vorname" placeholder="Vorname">
            </div>
            <div class="form-group">
                <label for="edit-nachname">Nachname</label>
                <input type="text" id="edit-nachname" placeholder="Nachname">
            </div>
        </div>

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-email">E-Mail</label>
                <input type="email" id="edit-email" placeholder="E-Mail">
            </div>
            <div class="form-group">
                <label for="edit-telefon">Telefon</label>
                <input type="tel" id="edit-telefon" placeholder="Telefon">
            </div>
        </div>

        <div class="form-group">
            <label for="edit-strasse">Strasse</label>
            <input type="text" id="edit-strasse" placeholder="Strasse">
        </div>

        <div class="info-grid">
            <div class="form-group">
                <label for="edit-plz">PLZ</label>
                <input type="text" id="edit-plz" placeholder="PLZ">
            </div>
            <div class="form-group">
                <label for="edit-stadt">Stadt</label>
                <input type="text" id="edit-stadt" placeholder="Stadt">
            </div>
        </div>

        <div class="form-group">
            <label for="edit-geburtsdatum">Geburtsdatum</label>
            <input type="date" id="edit-geburtsdatum">
        </div>

        <div class="modal-actions">
            <button class="btn btn-primary" id="btnSavePersonal" type="button">Speichern</button>
            <button class="btn btn-cancel" data-close="editPersonalModal" type="button">Abbrechen</button>
        </div>
    </div>
</div>

<!-- Modal: Change Username -->
<div id="changeUsernameModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Benutzername ändern</h2>
            <span class="close" data-close="changeUsernameModal">&times;</span>
        </div>

        <div class="form-group">
            <label for="username-currentPassword">Aktuelles Passwort</label>
            <input type="password" id="username-currentPassword" placeholder="Aktuelles Passwort">
        </div>

        <div class="form-group">
            <label for="username-newUsername">Neuer Benutzername</label>
            <input type="text" id="username-newUsername" placeholder="Neuer Benutzername">
        </div>

        <div class="modal-actions">
            <button class="btn btn-primary" id="btnSaveUsername" type="button">Speichern</button>
            <button class="btn btn-cancel" data-close="changeUsernameModal" type="button">Abbrechen</button>
        </div>
    </div>
</div>

<!-- Modal: Change Password -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Passwort ändern</h2>
            <span class="close" data-close="changePasswordModal">&times;</span>
        </div>

        <div class="form-group">
            <label for="password-currentPassword">Aktuelles Passwort</label>
            <input type="password" id="password-currentPassword" placeholder="Aktuelles Passwort">
        </div>

        <div class="form-group">
            <label for="password-newPassword">Neues Passwort</label>
            <input type="password" id="password-newPassword" placeholder="Neues Passwort (min. 8 Zeichen)">
        </div>

        <div class="form-group">
            <label for="password-confirmPassword">Passwort bestätigen</label>
            <input type="password" id="password-confirmPassword" placeholder="Neues Passwort bestätigen">
        </div>

        <div class="modal-actions">
            <button class="btn btn-primary" id="btnSavePassword" type="button">Speichern</button>
            <button class="btn btn-cancel" data-close="changePasswordModal" type="button">Abbrechen</button>
        </div>
    </div>
</div>

<script type="module" src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
