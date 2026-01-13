<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support - Yachthafen Plau</title>
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
            padding: 40px 20px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        body.dark-mode {
            background: #1a1a1a;
            color: #e0e0e0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        header {
            margin-bottom: 40px;
        }

        h1 {
            font-size: 36px;
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

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #0b3c5d;
            text-decoration: none;
            font-size: 14px;
        }

        body.dark-mode .back-link {
            color: #64b5f6;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .support-form {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        body.dark-mode .support-form {
            background: #2d2d2d;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        body.dark-mode label {
            color: #e0e0e0;
        }

        .required {
            color: #d32f2f;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            transition: border-color 0.3s ease, background 0.3s ease;
        }

        body.dark-mode input[type="text"],
        body.dark-mode input[type="email"],
        body.dark-mode input[type="tel"],
        body.dark-mode input[type="date"],
        body.dark-mode select,
        body.dark-mode textarea {
            background: #3a3a3a;
            border-color: #555;
            color: #e0e0e0;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #0b3c5d;
        }

        body.dark-mode input:focus,
        body.dark-mode select:focus,
        body.dark-mode textarea:focus {
            border-color: #64b5f6;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-button {
            background: #0b3c5d;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
        }

        .submit-button:hover {
            background: #083048;
        }

        body.dark-mode .submit-button {
            background: #64b5f6;
            color: #1a1a1a;
        }

        body.dark-mode .submit-button:hover {
            background: #5aa3e0;
        }

        .form-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        body.dark-mode .form-hint {
            color: #999;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 28px;
            }

            .support-form {
                padding: 25px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>
<body>

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
                <input type="text" id="bookingReference" name="bookingReference" placeholder="z.B. LP-2026-001 oder BV-2026-123">
                <p class="form-hint">Falls vorhanden, geben Sie Ihre Buchungs- oder Referenznummer an</p>
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
                <input type="text" id="subject" name="subject" placeholder="Kurze Zusammenfassung Ihres Anliegens" required>
            </div>

            <div class="form-group">
                <label for="description">Beschreibung <span class="required">*</span></label>
                <textarea id="description" name="description" placeholder="Bitte beschreiben Sie Ihr Anliegen so detailliert wie möglich..." required></textarea>
                <p class="form-hint">Je mehr Details Sie angeben, desto schneller können wir Ihnen helfen</p>
            </div>

            <button type="submit" class="submit-button">Ticket absenden</button>
        </form>
    </div>
</div>

<script>
    // Apply dark mode if enabled
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }

    // Form submission handler
    document.getElementById('supportForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Here you would normally send the form data to your backend
        alert('Vielen Dank für Ihre Anfrage! Wir werden uns in Kürze bei Ihnen melden.\n\nTicket-ID: #' + Math.floor(Math.random() * 10000));

        // Reset form
        this.reset();
    });
</script>

</body>
</html>
