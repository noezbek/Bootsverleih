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
    <title>Support - Yachthafen Plau</title>

    <link rel="stylesheet" href="<?= base_url('styles/support.css') ?>">
</head>
<body data-page="support">

<div class="container">
    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <h1>Support</h1>
        <p class="subtitle">Benötigen Sie Hilfe? Erstellen Sie ein Support-Ticket</p>
    </header>

    <div class="support-form">
        <form id="supportForm" method="post" action="<?= base_url('support/submit') ?>">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">Vorname <span class="required">*</span></label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>

                <div class="form-group">
                    <label for="lastName">Nachname <span class="required">*</span></label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">E-Mail <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Telefon</label>
                    <input type="tel" id="phone" name="phone">
                </div>
            </div>

            <div class="form-group">
                <label for="bookingReference">Buchungsnummer</label>
                <input type="text" id="bookingReference" name="bookingReference"
                       placeholder="z.B. LP-2026-001 oder BV-2026-123">
                <p class="form-hint">
                    Falls vorhanden, geben Sie Ihre Buchungs- oder Referenznummer an
                </p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category">Kategorie <span class="required">*</span></label>
                    <select id="category" name="category" required>
                        <option value="">Bitte wählen...</option>
                        <option value="liegeplatzbuchung">Liegeplatz-Buchung</option>
                        <option value="liegeplatzproblem">Liegeplatz-Problem</option>
                        <option value="bootsbuchung">Boots-Buchung</option>
                        <option value="bootsproblem">Boots-Problem</option>
                        <option value="zahlung">Zahlungen & Rechnungen</option>
                        <option value="anlage">Anlage & Einrichtungen</option>
                        <option value="schadensmeldung">Schadensmeldung</option>
                        <option value="stornierung">Stornierung & Umbuchung</option>
                        <option value="allgemein">Allgemeine Anfrage</option>
                        <option value="sonstiges">Sonstiges</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="priority">Priorität <span class="required">*</span></label>
                    <select id="priority" name="priority" required>
                        <option value="">Bitte wählen...</option>
                        <option value="niedrig">Niedrig</option>
                        <option value="mittel" selected>Mittel</option>
                        <option value="hoch">Hoch</option>
                        <option value="dringend">Dringend</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="incidentDate">Datum des Vorfalls</label>
                <input type="date" id="incidentDate" name="incidentDate">
                <p class="form-hint">Wann ist das Problem aufgetreten?</p>
            </div>

            <div class="form-group">
                <label for="subject">Betreff <span class="required">*</span></label>
                <input type="text" id="subject" name="subject"
                       placeholder="Kurze Zusammenfassung Ihres Anliegens" required>
            </div>

            <div class="form-group">
                <label for="description">Beschreibung <span class="required">*</span></label>
                <textarea id="description" name="description"
                          placeholder="Bitte beschreiben Sie Ihr Anliegen so detailliert wie möglich..."
                          required></textarea>
                <p class="form-hint">
                    Je mehr Details Sie angeben, desto schneller können wir Ihnen helfen
                </p>
            </div>

            <button type="submit" class="submit-button">Ticket absenden</button>
        </form>
    </div>
</div>

<script type="module" src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>
