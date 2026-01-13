<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zahlungen - Yachthafen Plau</title>
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
            max-width: 1200px;
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

        .payment-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        body.dark-mode .payment-card {
            background: #2d2d2d;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .payment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        body.dark-mode .payment-header {
            border-bottom-color: #444;
        }

        .payment-title {
            flex: 1;
        }

        .payment-title h2 {
            font-size: 22px;
            color: #0b3c5d;
            margin-bottom: 5px;
        }

        body.dark-mode .payment-title h2 {
            color: #64b5f6;
        }

        .payment-type {
            font-size: 14px;
            color: #666;
        }

        body.dark-mode .payment-type {
            color: #999;
        }

        .payment-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .status-active {
            background: #e8f5e9;
            color: #2e7d32;
        }

        body.dark-mode .status-active {
            background: #1b5e20;
            color: #a5d6a7;
        }

        .status-pending {
            background: #fff3e0;
            color: #e65100;
        }

        body.dark-mode .status-pending {
            background: #e65100;
            color: #ffe0b2;
        }

        .payment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        body.dark-mode .detail-label {
            color: #999;
        }

        .detail-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        body.dark-mode .detail-value {
            color: #e0e0e0;
        }

        .detail-value.price {
            color: #0b3c5d;
            font-size: 24px;
        }

        body.dark-mode .detail-value.price {
            color: #64b5f6;
        }

        .next-payment-highlight {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #0b3c5d;
        }

        body.dark-mode .next-payment-highlight {
            background: #1e3a52;
            border-left-color: #64b5f6;
        }

        .next-payment-highlight p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        body.dark-mode .next-payment-highlight p {
            color: #b0b0b0;
        }

        .next-payment-highlight .countdown {
            font-size: 20px;
            font-weight: bold;
            color: #0b3c5d;
        }

        body.dark-mode .next-payment-highlight .countdown {
            color: #64b5f6;
        }

        .payment-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #0b3c5d;
            color: white;
        }

        .btn-primary:hover {
            background: #083048;
        }

        body.dark-mode .btn-primary {
            background: #64b5f6;
            color: #1a1a1a;
        }

        body.dark-mode .btn-primary:hover {
            background: #5aa3e0;
        }

        .btn-secondary {
            background: white;
            color: #0b3c5d;
            border: 2px solid #0b3c5d;
        }

        .btn-secondary:hover {
            background: #0b3c5d;
            color: white;
        }

        body.dark-mode .btn-secondary {
            background: transparent;
            color: #64b5f6;
            border-color: #64b5f6;
        }

        body.dark-mode .btn-secondary:hover {
            background: #64b5f6;
            color: #1a1a1a;
        }

        .btn-danger {
            background: white;
            color: #d32f2f;
            border: 2px solid #d32f2f;
        }

        .btn-danger:hover {
            background: #d32f2f;
            color: white;
        }

        body.dark-mode .btn-danger {
            background: transparent;
            color: #ff6b6b;
            border-color: #c62828;
        }

        body.dark-mode .btn-danger:hover {
            background: #c62828;
            color: white;
        }

        .payment-history {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }

        body.dark-mode .payment-history {
            background: #2d2d2d;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .payment-history h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        body.dark-mode .payment-history h2 {
            color: #e0e0e0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px;
            background: #f5f5f5;
            color: #333;
            font-weight: bold;
            font-size: 14px;
        }

        body.dark-mode th {
            background: #3a3a3a;
            color: #e0e0e0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }

        body.dark-mode td {
            border-bottom-color: #444;
            color: #b0b0b0;
        }

        tr:hover {
            background: #fafafa;
        }

        body.dark-mode tr:hover {
            background: #3a3a3a;
        }

        .no-payments {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 28px;
            }

            .payment-card {
                padding: 20px;
            }

            .payment-header {
                flex-direction: column;
                gap: 15px;
            }

            .payment-details {
                grid-template-columns: 1fr;
            }

            .payment-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <h1>Zahlungen</h1>
        <p class="subtitle">Übersicht Ihrer aktiven Zahlungen und Abonnements</p>
    </header>

    <!-- Payment Card 1: Liegeplatz -->
    <div class="payment-card">
        <div class="payment-header">
            <div class="payment-title">
                <h2>Liegeplatz B-12</h2>
                <p class="payment-type">Jährliches Abonnement</p>
            </div>
            <span class="payment-status status-active">Aktiv</span>
        </div>

        <div class="next-payment-highlight">
            <p>Nächste Zahlung fällig in:</p>
            <span class="countdown">23 Tage (05.02.2026)</span>
        </div>

        <div class="payment-details">
            <div class="detail-item">
                <span class="detail-label">Zahlungsbetrag</span>
                <span class="detail-value price">1.250,00 €</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Zahlungsrhythmus</span>
                <span class="detail-value">Jährlich</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Vertragsbeginn</span>
                <span class="detail-value">05.02.2024</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Zahlungsmethode</span>
                <span class="detail-value">Lastschrift</span>
            </div>
        </div>

        <div class="payment-actions">
            <button class="btn btn-primary" onclick="changePaymentPlan('LP-B12')">Zahlungsplan ändern</button>
            <button class="btn btn-secondary" onclick="viewInvoices('LP-B12')">Rechnungen ansehen</button>
            <button class="btn btn-danger" onclick="cancelPayment('LP-B12', 'Liegeplatz B-12')">Kündigen</button>
        </div>
    </div>

    <!-- Payment Card 2: Bootsverleih -->
    <div class="payment-card">
        <div class="payment-header">
            <div class="payment-title">
                <h2>Bootsverleih - Segelboot "Windspiel"</h2>
                <p class="payment-type">Monatliches Abonnement</p>
            </div>
            <span class="payment-status status-active">Aktiv</span>
        </div>

        <div class="next-payment-highlight">
            <p>Nächste Zahlung fällig in:</p>
            <span class="countdown">8 Tage (21.01.2026)</span>
        </div>

        <div class="payment-details">
            <div class="detail-item">
                <span class="detail-label">Zahlungsbetrag</span>
                <span class="detail-value price">350,00 €</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Zahlungsrhythmus</span>
                <span class="detail-value">Monatlich</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Vertragsbeginn</span>
                <span class="detail-value">21.06.2025</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Zahlungsmethode</span>
                <span class="detail-value">Kreditkarte</span>
            </div>
        </div>

        <div class="payment-actions">
            <button class="btn btn-primary" onclick="changePaymentPlan('BV-WS01')">Zahlungsplan ändern</button>
            <button class="btn btn-secondary" onclick="viewInvoices('BV-WS01')">Rechnungen ansehen</button>
            <button class="btn btn-danger" onclick="cancelPayment('BV-WS01', 'Bootsverleih - Segelboot Windspiel')">Kündigen</button>
        </div>
    </div>

    <!-- Payment Card 3: Winterlager -->
    <div class="payment-card">
        <div class="payment-header">
            <div class="payment-title">
                <h2>Winterlager - Stellplatz 45</h2>
                <p class="payment-type">Einmalige Zahlung</p>
            </div>
            <span class="payment-status status-pending">Ausstehend</span>
        </div>

        <div class="next-payment-highlight">
            <p>Zahlung fällig in:</p>
            <span class="countdown">2 Tage (15.01.2026)</span>
        </div>

        <div class="payment-details">
            <div class="detail-item">
                <span class="detail-label">Zahlungsbetrag</span>
                <span class="detail-value price">580,00 €</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Zahlungsrhythmus</span>
                <span class="detail-value">Einmalig</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Buchungsdatum</span>
                <span class="detail-value">28.12.2025</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Zahlungsmethode</span>
                <span class="detail-value">Überweisung</span>
            </div>
        </div>

        <div class="payment-actions">
            <button class="btn btn-primary" onclick="payNow('WL-45')">Jetzt bezahlen</button>
            <button class="btn btn-secondary" onclick="viewInvoices('WL-45')">Rechnung ansehen</button>
            <button class="btn btn-danger" onclick="cancelPayment('WL-45', 'Winterlager - Stellplatz 45')">Stornieren</button>
        </div>
    </div>

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
            <tbody>
                <tr>
                    <td>21.12.2025</td>
                    <td>Bootsverleih - Segelboot "Windspiel"</td>
                    <td>350,00 €</td>
                    <td>Bezahlt</td>
                    <td><a href="#" onclick="downloadInvoice('INV-2025-234'); return false;" style="color: #0b3c5d;">PDF</a></td>
                </tr>
                <tr>
                    <td>21.11.2025</td>
                    <td>Bootsverleih - Segelboot "Windspiel"</td>
                    <td>350,00 €</td>
                    <td>Bezahlt</td>
                    <td><a href="#" onclick="downloadInvoice('INV-2025-198'); return false;" style="color: #0b3c5d;">PDF</a></td>
                </tr>
                <tr>
                    <td>05.02.2025</td>
                    <td>Liegeplatz B-12 (Jahresgebühr)</td>
                    <td>1.250,00 €</td>
                    <td>Bezahlt</td>
                    <td><a href="#" onclick="downloadInvoice('INV-2025-045'); return false;" style="color: #0b3c5d;">PDF</a></td>
                </tr>
                <tr>
                    <td>15.01.2025</td>
                    <td>Winterlager - Stellplatz 45</td>
                    <td>550,00 €</td>
                    <td>Bezahlt</td>
                    <td><a href="#" onclick="downloadInvoice('INV-2025-012'); return false;" style="color: #0b3c5d;">PDF</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Apply dark mode if enabled
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }

    function changePaymentPlan(paymentId) {
        alert('Zahlungsplan ändern für: ' + paymentId + '\n\nVerfügbare Optionen:\n- Monatlich\n- Vierteljährlich\n- Halbjährlich\n- Jährlich\n\nDiese Funktion wird in Kürze verfügbar sein.');
    }

    function viewInvoices(paymentId) {
        alert('Rechnungen anzeigen für: ' + paymentId + '\n\nHier werden alle zugehörigen Rechnungen aufgelistet.');
    }

    function cancelPayment(paymentId, description) {
        if (confirm('Möchten Sie wirklich die Zahlung für "' + description + '" kündigen?\n\nBeachten Sie: Die Kündigung wird zum Ende der aktuellen Abrechnungsperiode wirksam.')) {
            alert('Kündigungsantrag für ' + description + ' wurde erfolgreich eingereicht.\n\nSie erhalten eine Bestätigung per E-Mail.');
        }
    }

    function payNow(paymentId) {
        alert('Zahlung durchführen für: ' + paymentId + '\n\nSie werden zum Zahlungsportal weitergeleitet.');
    }

    function downloadInvoice(invoiceId) {
        alert('Rechnung ' + invoiceId + ' wird heruntergeladen...');
    }
</script>

</body>
</html>
