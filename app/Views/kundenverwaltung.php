<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kundenverwaltung - Yachthafen Plau</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding: 40px 20px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        body.dark-mode { background: #1a1a1a; color: #e0e0e0; }

        .container { max-width: 1400px; margin: 0 auto; }

        header {
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-left h1 { font-size: 36px; color: #333; margin-bottom: 10px; }
        body.dark-mode .header-left h1 { color: #e0e0e0; }

        .subtitle { font-size: 16px; color: #666; }
        body.dark-mode .subtitle { color: #999; }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #0b3c5d;
            text-decoration: none;
            font-size: 14px;
        }
        body.dark-mode .back-link { color: #64b5f6; }
        .back-link:hover { text-decoration: underline; }

        .search-bar { display: flex; gap: 10px; margin-bottom: 30px; }
        .search-bar input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        body.dark-mode .search-bar input {
            background: #3a3a3a;
            border-color: #555;
            color: #e0e0e0;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary { background: #0b3c5d; color: white; }
        .btn-primary:hover { background: #083048; }

        body.dark-mode .btn-primary { background: #64b5f6; color: #1a1a1a; }
        body.dark-mode .btn-primary:hover { background: #5aa3e0; }

        .customers-table {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        body.dark-mode .customers-table {
            background: #2d2d2d;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        table { width: 100%; border-collapse: collapse; min-width: 1000px; }

        th {
            text-align: left;
            padding: 15px 12px;
            background: #f5f5f5;
            color: #333;
            font-weight: bold;
            font-size: 14px;
            border-bottom: 2px solid #e0e0e0;
        }

        body.dark-mode th {
            background: #3a3a3a;
            color: #e0e0e0;
            border-bottom-color: #555;
        }

        td {
            padding: 15px 12px;
            border-bottom: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }

        body.dark-mode td { border-bottom-color: #444; color: #b0b0b0; }

        tr:hover { background: #fafafa; }
        body.dark-mode tr:hover { background: #3a3a3a; }

        .customer-name { font-weight: bold; color: #0b3c5d; }
        body.dark-mode .customer-name { color: #64b5f6; }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active { background: #e8f5e9; color: #2e7d32; }
        body.dark-mode .status-active { background: #1b5e20; color: #a5d6a7; }

        .status-inactive { background: #ffebee; color: #c62828; }
        body.dark-mode .status-inactive { background: #c62828; color: #ffcdd2; }

        .action-buttons { display: flex; gap: 8px; flex-wrap: wrap; }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-view { background: #0b3c5d; color: white; }
        .btn-view:hover { background: #083048; }
        body.dark-mode .btn-view { background: #64b5f6; color: #1a1a1a; }

        .btn-edit { background: #ff9800; color: white; }
        .btn-edit:hover { background: #f57c00; }

        .btn-delete { background: #d32f2f; color: white; }
        .btn-delete:hover { background: #b71c1c; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 40px;
            border-radius: 12px;
            max-width: 900px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .modal-content { background: #2d2d2d; }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        body.dark-mode .modal-header { border-bottom-color: #444; }

        .modal-header h2 { font-size: 28px; color: #333; }
        body.dark-mode .modal-header h2 { color: #e0e0e0; }

        .close {
            font-size: 32px;
            font-weight: bold;
            color: #999;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover { color: #333; }
        body.dark-mode .close:hover { color: #e0e0e0; }

        .info-section { margin-bottom: 30px; }

        .info-section h3 { font-size: 20px; color: #0b3c5d; margin-bottom: 15px; }
        body.dark-mode .info-section h3 { color: #64b5f6; }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item { display: flex; flex-direction: column; }

        .info-label { font-size: 13px; color: #666; margin-bottom: 5px; }
        body.dark-mode .info-label { color: #999; }

        .info-value { font-size: 16px; color: #333; font-weight: 500; }
        body.dark-mode .info-value { color: #e0e0e0; }

        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        body.dark-mode .form-group label { color: #e0e0e0; }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        body.dark-mode .form-group input,
        body.dark-mode .form-group select {
            background: #3a3a3a;
            border-color: #555;
            color: #e0e0e0;
        }

        .modal-actions { display: flex; gap: 15px; margin-top: 30px; }

        .btn-cancel { background: #999; color: white; }
        .btn-cancel:hover { background: #777; }

        .history-item {
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #0b3c5d;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        body.dark-mode .history-item {
            background: #3a3a3a;
            border-left-color: #64b5f6;
        }

        @media (max-width: 768px) {
            header { flex-direction: column; align-items: flex-start; }
            .header-left h1 { font-size: 28px; }
            .search-bar { flex-direction: column; }
            .customers-table { padding: 15px; }
            .modal-content { margin: 20px; padding: 25px; }
            .info-grid { grid-template-columns: 1fr; }
            .action-buttons { flex-direction: column; }
            .btn-small { width: 100%; }
        }
    </style>
</head>

<body>

<div class="container">

    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <div class="header-left">
            <h1>Kundenverwaltung</h1>
            <p class="subtitle">Verwaltung aller Kundenkonten und Stammdaten</p>
        </div>
        <button class="btn btn-primary" onclick="addNewCustomer()">+ Neuer Kunde</button>
    </header>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Suche nach Name, E-Mail, ID..." onkeyup="searchCustomers()">
        <button class="btn btn-primary" onclick="searchCustomers()">Suchen</button>
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
            <?php foreach ($kunden as $id => $kunde): ?>
                <tr data-customer-id="<?= esc($id) ?>">
                    <td><?= esc($id) ?></td>

                    <td class="customer-name">
                        <?= esc($kunde['vorname']) ?> <?= esc($kunde['nachname']) ?>
                    </td>

                    <td><?= esc($kunde['email']) ?></td>
                    <td><?= esc($kunde['telefon']) ?></td>

                    <td>
                        <?php if ((int)($kunde['active'] ?? 1) === 1): ?>
                            <span class="status-badge status-active">Aktiv</span>
                        <?php else: ?>
                            <span class="status-badge status-inactive">Inaktiv</span>
                        <?php endif; ?>
                    </td>

                    <td><?= esc($kunde['geburtsdatum'] ?? '-') ?></td>

                    <td>
                        <div class="action-buttons">
                            <button class="btn-small btn-view" onclick="viewCustomer(<?= esc($id) ?>)">Details</button>
                            <button class="btn-small btn-edit" onclick="editCustomer(<?= esc($id) ?>)">Bearbeiten</button>
                            <form action="deletekundenverwaltung" method="POST" style="display:inline;"
                                  onsubmit="return deleteCustomer(<?= esc($id) ?>, '<?= esc($kunde['vorname'] . ' ' . $kunde['nachname']) ?>')">
                                <input type="hidden" name="id" value="<?= esc($id) ?>">
                                <button type="submit" class="btn-small btn-delete">Löschen</button>
                            </form>

                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<!-- View Customer Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Kundendetails</h2>
            <span class="close" onclick="closeModal('viewModal')">&times;</span>
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
                    <span class="info-value" id="view-registered"></span>
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

<!-- Edit Customer Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Kunde bearbeiten</h2>
            <span class="close" onclick="closeModal('editModal')">&times;</span>
        </div>

        <form id="editForm" action="savekundenverwaltung" method="POST">
            <input type="hidden" id="edit-customerId" name="id">

            <!-- NEU: Geburtsdatum als DATE (nur bei neuen Kunden sichtbar & aktiv) -->
            <div class="form-group" id="geburtsdatumNewWrapper" style="display:none;">
                <label for="edit-geburtsdatum-new">Geburtsdatum</label>
                <input type="date" id="edit-geburtsdatum-new" name="geburtsdatum" disabled>
            </div>

            <!-- BESTEHEND: Geburtsdatum hidden (nur bei bestehenden Kunden aktiv) -->
            <input type="hidden" id="edit-geburtsdatum-hidden" name="geburtsdatum">

            <div class="info-grid">
                <div class="form-group">
                    <label for="edit-firstName">Vorname</label>
                    <input type="text" id="edit-firstName" name="vorname" required>
                </div>
                <div class="form-group">
                    <label for="edit-lastName">Nachname</label>
                    <input type="text" id="edit-lastName" name="nachname" required>
                </div>
            </div>

            <div class="info-grid">
                <div class="form-group">
                    <label for="edit-email">E-Mail</label>
                    <input type="email" id="edit-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="edit-phone">Telefon</label>
                    <input type="tel" id="edit-phone" name="telefon" required>
                </div>
            </div>

            <div class="form-group">
                <label for="edit-address">Adresse</label>
                <input type="text" id="edit-address" name="strasse" required>
            </div>

            <div class="info-grid">
                <div class="form-group">
                    <label for="edit-city">Stadt</label>
                    <input type="text" id="edit-city" name="stadt" required>
                </div>
                <div class="form-group">
                    <label for="edit-zip">PLZ</label>
                    <input type="text" id="edit-zip" name="plz" required>
                </div>
            </div>

            <div class="form-group">
                <label for="edit-status">Status</label>
                <select id="edit-status" name="active">
                    <option value="1">Aktiv</option>
                    <option value="0">Inaktiv</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Speichern</button>
                <button type="button" class="btn btn-cancel" onclick="closeModal('editModal')">Abbrechen</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Dark mode
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }

    // Backend-Kunden für JavaScript verfügbar machen
    const customers = <?= json_encode($kunden, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    function searchCustomers() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const table = document.getElementById('customersTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        }
    }

    function viewCustomer(customerId) {
        const customer = customers[customerId];

        document.getElementById('view-customerId').textContent = customerId;
        document.getElementById('view-name').textContent = (customer.vorname ?? '') + ' ' + (customer.nachname ?? '');
        document.getElementById('view-email').textContent = customer.email ?? '';
        document.getElementById('view-phone').textContent = customer.telefon ?? '';
        document.getElementById('view-address').textContent = (customer.strasse ?? '') + ', ' + (customer.plz ?? '') + ' ' + (customer.stadt ?? '');
        document.getElementById('view-registered').textContent = customer.geburtsdatum ?? '-';

        document.getElementById('viewModal').style.display = 'block';
    }

    function editCustomer(customerId) {
        const customer = customers[customerId];

        // ID setzen
        document.getElementById('edit-customerId').value = customerId;

        // BESTEHEND: date-input ausblenden & DISABLED
        document.getElementById('geburtsdatumNewWrapper').style.display = 'none';
        document.getElementById('edit-geburtsdatum-new').disabled = true;
        document.getElementById('edit-geburtsdatum-new').value = '';

        // BESTEHEND: hidden aktiv und füllen
        document.getElementById('edit-geburtsdatum-hidden').disabled = false;
        document.getElementById('edit-geburtsdatum-hidden').value = customer.geburtsdatum ?? '';

        // andere Felder
        document.getElementById('edit-firstName').value = customer.vorname ?? '';
        document.getElementById('edit-lastName').value = customer.nachname ?? '';
        document.getElementById('edit-email').value = customer.email ?? '';
        document.getElementById('edit-phone').value = customer.telefon ?? '';
        document.getElementById('edit-address').value = customer.strasse ?? '';
        document.getElementById('edit-city').value = customer.stadt ?? '';
        document.getElementById('edit-zip').value = customer.plz ?? '';
        document.getElementById('edit-status').value = (customer.active ? '1' : '0');

        document.getElementById('editModal').style.display = 'block';
    }

    function addNewCustomer() {
        // Neue ID leer
        document.getElementById('edit-customerId').value = '';

        // NEU: hidden geburtsdatum DISABLED (damit es NICHT mitgeschickt wird)
        document.getElementById('edit-geburtsdatum-hidden').disabled = true;
        document.getElementById('edit-geburtsdatum-hidden').value = '';

        // NEU: date-input sichtbar und AKTIV
        document.getElementById('geburtsdatumNewWrapper').style.display = 'block';
        document.getElementById('edit-geburtsdatum-new').disabled = false;
        document.getElementById('edit-geburtsdatum-new').value = '';

        // Inputs leeren
        document.getElementById('edit-firstName').value = '';
        document.getElementById('edit-lastName').value = '';
        document.getElementById('edit-email').value = '';
        document.getElementById('edit-phone').value = '';
        document.getElementById('edit-address').value = '';
        document.getElementById('edit-city').value = '';
        document.getElementById('edit-zip').value = '';

        // Status default aktiv
        document.getElementById('edit-status').value = '1';

        // Modal öffnen
        document.getElementById('editModal').style.display = 'block';
    }

    function deleteCustomer(customerId, customerName) {
        const ok = confirm('Möchten Sie den Kunden "' + customerName + '" wirklich löschen?\n\nDiese Aktion kann nicht rückgängig gemacht werden!');
        if (!ok) return false;

        alert('Kunde "' + customerName + '" wurde erfolgreich gelöscht.');
        return true; // WICHTIG: damit POST wirklich rausgeht
    }


    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    window.onclick = function (event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
</script>

</body>
</html>
