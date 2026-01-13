<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kundenverwaltung - Yachthafen Plau</title>
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
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-left h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
        }

        body.dark-mode .header-left h1 {
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

        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

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

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

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

        .customer-name {
            font-weight: bold;
            color: #0b3c5d;
        }

        body.dark-mode .customer-name {
            color: #64b5f6;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
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

        .status-inactive {
            background: #ffebee;
            color: #c62828;
        }

        body.dark-mode .status-inactive {
            background: #c62828;
            color: #ffcdd2;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: #0b3c5d;
            color: white;
        }

        .btn-view:hover {
            background: #083048;
        }

        body.dark-mode .btn-view {
            background: #64b5f6;
            color: #1a1a1a;
        }

        .btn-edit {
            background: #ff9800;
            color: white;
        }

        .btn-edit:hover {
            background: #f57c00;
        }

        .btn-delete {
            background: #d32f2f;
            color: white;
        }

        .btn-delete:hover {
            background: #b71c1c;
        }

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

        body.dark-mode .modal-content {
            background: #2d2d2d;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        body.dark-mode .modal-header {
            border-bottom-color: #444;
        }

        .modal-header h2 {
            font-size: 28px;
            color: #333;
        }

        body.dark-mode .modal-header h2 {
            color: #e0e0e0;
        }

        .close {
            font-size: 32px;
            font-weight: bold;
            color: #999;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: #333;
        }

        body.dark-mode .close:hover {
            color: #e0e0e0;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section h3 {
            font-size: 20px;
            color: #0b3c5d;
            margin-bottom: 15px;
        }

        body.dark-mode .info-section h3 {
            color: #64b5f6;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        body.dark-mode .info-label {
            color: #999;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        body.dark-mode .info-value {
            color: #e0e0e0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        body.dark-mode .form-group label {
            color: #e0e0e0;
        }

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

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-cancel {
            background: #999;
            color: white;
        }

        .btn-cancel:hover {
            background: #777;
        }

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

        .history-date {
            font-size: 12px;
            color: #999;
            margin-bottom: 5px;
        }

        .history-description {
            font-size: 14px;
            color: #333;
        }

        body.dark-mode .history-description {
            color: #e0e0e0;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-left h1 {
                font-size: 28px;
            }

            .search-bar {
                flex-direction: column;
            }

            .customers-table {
                padding: 15px;
            }

            .modal-content {
                margin: 20px;
                padding: 25px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-small {
                width: 100%;
            }
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
        <input type="text" id="searchInput" placeholder="Suche nach Name, E-Mail, Kundennummer..." onkeyup="searchCustomers()">
        <button class="btn btn-primary" onclick="searchCustomers()">Suchen</button>
    </div>

    <div class="customers-table">
        <table id="customersTable">
            <thead>
                <tr>
                    <th>Kunden-Nr.</th>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Telefon</th>
                    <th>Status</th>
                    <th>Registriert</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <tr data-customer-id="K001">
                    <td>K-2024-001</td>
                    <td class="customer-name">Max Müller</td>
                    <td>max.mueller@email.de</td>
                    <td>+49 170 1234567</td>
                    <td><span class="status-badge status-active">Aktiv</span></td>
                    <td>15.03.2024</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-small btn-view" onclick="viewCustomer('K001')">Details</button>
                            <button class="btn-small btn-edit" onclick="editCustomer('K001')">Bearbeiten</button>
                            <button class="btn-small btn-delete" onclick="deleteCustomer('K001', 'Max Müller')">Löschen</button>
                        </div>
                    </td>
                </tr>
                <tr data-customer-id="K002">
                    <td>K-2024-002</td>
                    <td class="customer-name">Anna Schmidt</td>
                    <td>anna.schmidt@email.de</td>
                    <td>+49 172 9876543</td>
                    <td><span class="status-badge status-active">Aktiv</span></td>
                    <td>22.04.2024</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-small btn-view" onclick="viewCustomer('K002')">Details</button>
                            <button class="btn-small btn-edit" onclick="editCustomer('K002')">Bearbeiten</button>
                            <button class="btn-small btn-delete" onclick="deleteCustomer('K002', 'Anna Schmidt')">Löschen</button>
                        </div>
                    </td>
                </tr>
                <tr data-customer-id="K003">
                    <td>K-2023-087</td>
                    <td class="customer-name">Thomas Weber</td>
                    <td>thomas.weber@email.de</td>
                    <td>+49 151 5551234</td>
                    <td><span class="status-badge status-active">Aktiv</span></td>
                    <td>10.08.2023</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-small btn-view" onclick="viewCustomer('K003')">Details</button>
                            <button class="btn-small btn-edit" onclick="editCustomer('K003')">Bearbeiten</button>
                            <button class="btn-small btn-delete" onclick="deleteCustomer('K003', 'Thomas Weber')">Löschen</button>
                        </div>
                    </td>
                </tr>
                <tr data-customer-id="K004">
                    <td>K-2025-034</td>
                    <td class="customer-name">Julia Fischer</td>
                    <td>julia.fischer@email.de</td>
                    <td>+49 160 7778888</td>
                    <td><span class="status-badge status-active">Aktiv</span></td>
                    <td>05.01.2025</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-small btn-view" onclick="viewCustomer('K004')">Details</button>
                            <button class="btn-small btn-edit" onclick="editCustomer('K004')">Bearbeiten</button>
                            <button class="btn-small btn-delete" onclick="deleteCustomer('K004', 'Julia Fischer')">Löschen</button>
                        </div>
                    </td>
                </tr>
                <tr data-customer-id="K005">
                    <td>K-2023-012</td>
                    <td class="customer-name">Peter Schneider</td>
                    <td>peter.schneider@email.de</td>
                    <td>+49 175 4443332</td>
                    <td><span class="status-badge status-inactive">Inaktiv</span></td>
                    <td>18.02.2023</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-small btn-view" onclick="viewCustomer('K005')">Details</button>
                            <button class="btn-small btn-edit" onclick="editCustomer('K005')">Bearbeiten</button>
                            <button class="btn-small btn-delete" onclick="deleteCustomer('K005', 'Peter Schneider')">Löschen</button>
                        </div>
                    </td>
                </tr>
                <tr data-customer-id="K006">
                    <td>K-2024-089</td>
                    <td class="customer-name">Sarah Bauer</td>
                    <td>sarah.bauer@email.de</td>
                    <td>+49 162 1112223</td>
                    <td><span class="status-badge status-active">Aktiv</span></td>
                    <td>12.11.2024</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-small btn-view" onclick="viewCustomer('K006')">Details</button>
                            <button class="btn-small btn-edit" onclick="editCustomer('K006')">Bearbeiten</button>
                            <button class="btn-small btn-delete" onclick="deleteCustomer('K006', 'Sarah Bauer')">Löschen</button>
                        </div>
                    </td>
                </tr>
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
                    <span class="info-label">Kundennummer</span>
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
                    <span class="info-label">Registriert seit</span>
                    <span class="info-value" id="view-registered"></span>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>Zahlungsinformationen</h3>
            <div id="view-payments"></div>
        </div>

        <div class="info-section">
            <h3>Bestellhistorie</h3>
            <div id="view-orders"></div>
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

        <form id="editForm" onsubmit="saveCustomer(event)">
            <input type="hidden" id="edit-customerId">

            <div class="info-grid">
                <div class="form-group">
                    <label for="edit-firstName">Vorname</label>
                    <input type="text" id="edit-firstName" required>
                </div>
                <div class="form-group">
                    <label for="edit-lastName">Nachname</label>
                    <input type="text" id="edit-lastName" required>
                </div>
            </div>

            <div class="info-grid">
                <div class="form-group">
                    <label for="edit-email">E-Mail</label>
                    <input type="email" id="edit-email" required>
                </div>
                <div class="form-group">
                    <label for="edit-phone">Telefon</label>
                    <input type="tel" id="edit-phone" required>
                </div>
            </div>

            <div class="form-group">
                <label for="edit-address">Adresse</label>
                <input type="text" id="edit-address" required>
            </div>

            <div class="info-grid">
                <div class="form-group">
                    <label for="edit-city">Stadt</label>
                    <input type="text" id="edit-city" required>
                </div>
                <div class="form-group">
                    <label for="edit-zip">PLZ</label>
                    <input type="text" id="edit-zip" required>
                </div>
            </div>

            <div class="form-group">
                <label for="edit-status">Status</label>
                <select id="edit-status">
                    <option value="active">Aktiv</option>
                    <option value="inactive">Inaktiv</option>
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
    // Apply dark mode if enabled
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }

    // Dummy customer data
    const customers = {
        'K001': {
            id: 'K-2024-001',
            firstName: 'Max',
            lastName: 'Müller',
            email: 'max.mueller@email.de',
            phone: '+49 170 1234567',
            address: 'Hauptstraße 15',
            city: 'Plau am See',
            zip: '19395',
            registered: '15.03.2024',
            status: 'active',
            payments: [
                { date: '21.12.2025', description: 'Liegeplatz B-12', amount: '1.250,00 €', status: 'Bezahlt' },
                { date: '15.03.2024', description: 'Bootsverleih Kaution', amount: '500,00 €', status: 'Bezahlt' }
            ],
            orders: [
                { date: '15.03.2024', type: 'Liegeplatz-Buchung', item: 'Liegeplatz B-12', duration: 'Jahresvertrag' },
                { date: '22.05.2024', type: 'Bootsverleih', item: 'Segelboot "Windspiel"', duration: '3 Tage' }
            ]
        },
        'K002': {
            id: 'K-2024-002',
            firstName: 'Anna',
            lastName: 'Schmidt',
            email: 'anna.schmidt@email.de',
            phone: '+49 172 9876543',
            address: 'Seestraße 42',
            city: 'Plau am See',
            zip: '19395',
            registered: '22.04.2024',
            status: 'active',
            payments: [
                { date: '10.12.2025', description: 'Bootsverleih Monatlich', amount: '350,00 €', status: 'Bezahlt' },
                { date: '22.04.2024', description: 'Anmeldegebühr', amount: '50,00 €', status: 'Bezahlt' }
            ],
            orders: [
                { date: '22.04.2024', type: 'Bootsverleih', item: 'Motorboot "Poseidon"', duration: 'Monatlich' },
                { date: '15.07.2024', type: 'Zubehör', item: 'Schwimmwesten (4x)', duration: 'Einmalig' }
            ]
        },
        'K003': {
            id: 'K-2023-087',
            firstName: 'Thomas',
            lastName: 'Weber',
            email: 'thomas.weber@email.de',
            phone: '+49 151 5551234',
            address: 'Uferweg 8',
            city: 'Malchow',
            zip: '17213',
            registered: '10.08.2023',
            status: 'active',
            payments: [
                { date: '05.01.2026', description: 'Liegeplatz A-05', amount: '980,00 €', status: 'Bezahlt' },
                { date: '05.01.2025', description: 'Liegeplatz A-05', amount: '950,00 €', status: 'Bezahlt' }
            ],
            orders: [
                { date: '10.08.2023', type: 'Liegeplatz-Buchung', item: 'Liegeplatz A-05', duration: 'Jahresvertrag' },
                { date: '20.06.2024', type: 'Winterlager', item: 'Stellplatz 12', duration: 'Oktober-März' }
            ]
        },
        'K004': {
            id: 'K-2025-034',
            firstName: 'Julia',
            lastName: 'Fischer',
            email: 'julia.fischer@email.de',
            phone: '+49 160 7778888',
            address: 'Fischerweg 23',
            city: 'Waren',
            zip: '17192',
            registered: '05.01.2025',
            status: 'active',
            payments: [
                { date: '05.01.2025', description: 'Bootsverleih Wochenende', amount: '280,00 €', status: 'Bezahlt' }
            ],
            orders: [
                { date: '05.01.2025', type: 'Bootsverleih', item: 'Kajak "Forelle"', duration: 'Wochenende' }
            ]
        },
        'K005': {
            id: 'K-2023-012',
            firstName: 'Peter',
            lastName: 'Schneider',
            email: 'peter.schneider@email.de',
            phone: '+49 175 4443332',
            address: 'Bergstraße 67',
            city: 'Röbel',
            zip: '17207',
            registered: '18.02.2023',
            status: 'inactive',
            payments: [
                { date: '20.02.2024', description: 'Liegeplatz C-22', amount: '1.100,00 €', status: 'Bezahlt' }
            ],
            orders: [
                { date: '18.02.2023', type: 'Liegeplatz-Buchung', item: 'Liegeplatz C-22', duration: 'Jahresvertrag (beendet)' }
            ]
        },
        'K006': {
            id: 'K-2024-089',
            firstName: 'Sarah',
            lastName: 'Bauer',
            email: 'sarah.bauer@email.de',
            phone: '+49 162 1112223',
            address: 'Gartenweg 5',
            city: 'Plau am See',
            zip: '19395',
            registered: '12.11.2024',
            status: 'active',
            payments: [
                { date: '28.12.2025', description: 'Winterlager', amount: '580,00 €', status: 'Ausstehend' }
            ],
            orders: [
                { date: '12.11.2024', type: 'Winterlager', item: 'Stellplatz 45', duration: 'November-März' }
            ]
        }
    };

    function searchCustomers() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const table = document.getElementById('customersTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();

            if (text.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    function viewCustomer(customerId) {
        const customer = customers[customerId];

        document.getElementById('view-customerId').textContent = customer.id;
        document.getElementById('view-name').textContent = customer.firstName + ' ' + customer.lastName;
        document.getElementById('view-email').textContent = customer.email;
        document.getElementById('view-phone').textContent = customer.phone;
        document.getElementById('view-address').textContent = customer.address + ', ' + customer.zip + ' ' + customer.city;
        document.getElementById('view-registered').textContent = customer.registered;

        // Payments
        let paymentsHtml = '';
        customer.payments.forEach(payment => {
            paymentsHtml += `
                <div class="history-item">
                    <div class="history-date">${payment.date}</div>
                    <div class="history-description">
                        <strong>${payment.description}</strong> - ${payment.amount} (${payment.status})
                    </div>
                </div>
            `;
        });
        document.getElementById('view-payments').innerHTML = paymentsHtml;

        // Orders
        let ordersHtml = '';
        customer.orders.forEach(order => {
            ordersHtml += `
                <div class="history-item">
                    <div class="history-date">${order.date}</div>
                    <div class="history-description">
                        <strong>${order.type}:</strong> ${order.item} - ${order.duration}
                    </div>
                </div>
            `;
        });
        document.getElementById('view-orders').innerHTML = ordersHtml;

        document.getElementById('viewModal').style.display = 'block';
    }

    function editCustomer(customerId) {
        const customer = customers[customerId];

        document.getElementById('edit-customerId').value = customerId;
        document.getElementById('edit-firstName').value = customer.firstName;
        document.getElementById('edit-lastName').value = customer.lastName;
        document.getElementById('edit-email').value = customer.email;
        document.getElementById('edit-phone').value = customer.phone;
        document.getElementById('edit-address').value = customer.address;
        document.getElementById('edit-city').value = customer.city;
        document.getElementById('edit-zip').value = customer.zip;
        document.getElementById('edit-status').value = customer.status;

        document.getElementById('editModal').style.display = 'block';
    }

    function saveCustomer(event) {
        event.preventDefault();

        const customerId = document.getElementById('edit-customerId').value;
        const firstName = document.getElementById('edit-firstName').value;
        const lastName = document.getElementById('edit-lastName').value;

        alert('Kundendaten für ' + firstName + ' ' + lastName + ' wurden erfolgreich aktualisiert!');

        closeModal('editModal');

        // Here you would normally send the data to your backend
    }

    function deleteCustomer(customerId, customerName) {
        if (confirm('Möchten Sie den Kunden "' + customerName + '" wirklich löschen?\n\nDiese Aktion kann nicht rückgängig gemacht werden!')) {
            alert('Kunde "' + customerName + '" wurde erfolgreich gelöscht.');

            // Remove the row from the table
            const row = document.querySelector(`tr[data-customer-id="${customerId}"]`);
            if (row) {
                row.remove();
            }
        }
    }

    function addNewCustomer() {
        alert('Neuen Kunden hinzufügen\n\nDiese Funktion wird in Kürze verfügbar sein.');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
</script>

</body>
</html>
