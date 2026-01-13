<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootsverleih - Yachthafen Plau</title>
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

        .filters {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        body.dark-mode .filters {
            background: #2d2d2d;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-size: 13px;
            font-weight: bold;
            color: #666;
        }

        body.dark-mode .filter-group label {
            color: #999;
        }

        .filter-group select,
        .filter-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            min-width: 150px;
        }

        body.dark-mode .filter-group select,
        body.dark-mode .filter-group input {
            background: #3a3a3a;
            border-color: #555;
            color: #e0e0e0;
        }

        .boats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .boat-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        body.dark-mode .boat-card {
            background: #2d2d2d;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .boat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        body.dark-mode .boat-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        .boat-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #0b3c5d, #1ca3ec);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            color: white;
        }

        .boat-content {
            padding: 25px;
        }

        .boat-header {
            margin-bottom: 15px;
        }

        .boat-name {
            font-size: 22px;
            color: #0b3c5d;
            margin-bottom: 5px;
            font-weight: bold;
        }

        body.dark-mode .boat-name {
            color: #64b5f6;
        }

        .boat-type {
            font-size: 13px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .boat-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
            padding: 15px 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        body.dark-mode .boat-specs {
            border-color: #444;
        }

        .spec-item {
            font-size: 13px;
            color: #666;
        }

        body.dark-mode .spec-item {
            color: #b0b0b0;
        }

        .spec-label {
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }

        .boat-pricing {
            margin-bottom: 20px;
        }

        .price {
            font-size: 28px;
            font-weight: bold;
            color: #0b3c5d;
        }

        body.dark-mode .price {
            color: #64b5f6;
        }

        .price-period {
            font-size: 14px;
            color: #999;
        }

        .availability-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .available {
            background: #e8f5e9;
            color: #2e7d32;
        }

        body.dark-mode .available {
            background: #1b5e20;
            color: #a5d6a7;
        }

        .limited {
            background: #fff3e0;
            color: #e65100;
        }

        body.dark-mode .limited {
            background: #e65100;
            color: #ffe0b2;
        }

        .btn-rent {
            width: 100%;
            padding: 14px;
            background: #0b3c5d;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-rent:hover {
            background: #083048;
        }

        body.dark-mode .btn-rent {
            background: #64b5f6;
            color: #1a1a1a;
        }

        body.dark-mode .btn-rent:hover {
            background: #5aa3e0;
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
            max-width: 600px;
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

        .form-group {
            margin-bottom: 25px;
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

        .cost-summary {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        body.dark-mode .cost-summary {
            background: #3a3a3a;
        }

        .cost-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
            color: #666;
        }

        body.dark-mode .cost-line {
            color: #b0b0b0;
        }

        .cost-total {
            display: flex;
            justify-content: space-between;
            font-size: 20px;
            font-weight: bold;
            color: #0b3c5d;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
            margin-top: 10px;
        }

        body.dark-mode .cost-total {
            color: #64b5f6;
            border-top-color: #555;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
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

        .btn-secondary {
            background: #999;
            color: white;
        }

        .btn-secondary:hover {
            background: #777;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 28px;
            }

            .filters {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group select,
            .filter-group input {
                width: 100%;
            }

            .boats-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                margin: 20px;
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <h1>Bootsverleih</h1>
        <p class="subtitle">Wählen Sie Ihr perfektes Boot für Ihr nächstes Abenteuer</p>
    </header>

    <div class="filters">
        <div class="filter-group">
            <label for="sortBy">Sortieren nach</label>
            <select id="sortBy" onchange="sortBoats()">
                <option value="name">Name</option>
                <option value="price-low">Preis (niedrig-hoch)</option>
                <option value="price-high">Preis (hoch-niedrig)</option>
                <option value="capacity">Kapazität</option>
                <option value="type">Bootstyp</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="filterType">Bootstyp</label>
            <select id="filterType" onchange="filterBoats()">
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
            <select id="filterCapacity" onchange="filterBoats()">
                <option value="0">Alle</option>
                <option value="2">2+ Personen</option>
                <option value="4">4+ Personen</option>
                <option value="6">6+ Personen</option>
            </select>
        </div>
    </div>

    <div class="boats-grid" id="boatsGrid">
        <!-- Boats will be rendered here by JavaScript -->
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-boat-name">Boot buchen</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>

        <form id="bookingForm" onsubmit="submitBooking(event)">
            <input type="hidden" id="booking-boat-id">

            <div class="form-group">
                <label for="booking-start-date">Startdatum</label>
                <input type="date" id="booking-start-date" required onchange="calculateCost()">
            </div>

            <div class="form-group">
                <label for="booking-duration">Mietdauer</label>
                <select id="booking-duration" required onchange="calculateCost()">
                    <option value="">Bitte wählen...</option>
                    <option value="0.5">Halbtag (4 Stunden)</option>
                    <option value="1">1 Tag</option>
                    <option value="2">2 Tage</option>
                    <option value="3">3 Tage</option>
                    <option value="7">1 Woche</option>
                    <option value="14">2 Wochen</option>
                    <option value="30">1 Monat</option>
                </select>
            </div>

            <div class="form-group">
                <label for="booking-name">Ihr Name</label>
                <input type="text" id="booking-name" required>
            </div>

            <div class="form-group">
                <label for="booking-email">E-Mail</label>
                <input type="email" id="booking-email" required>
            </div>

            <div class="form-group">
                <label for="booking-phone">Telefon</label>
                <input type="tel" id="booking-phone" required>
            </div>

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
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Abbrechen</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Apply dark mode if enabled
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }

    // Boat data
    const boats = [
        {
            id: 1,
            name: 'Windspiel',
            type: 'segelboot',
            icon: '⛵',
            capacity: 6,
            length: '8.5m',
            pricePerDay: 120,
            deposit: 500,
            availability: 'available',
            features: 'Kajüte, WC'
        },
        {
            id: 2,
            name: 'Poseidon',
            type: 'motorboot',
            icon: '🚤',
            capacity: 8,
            length: '7.2m',
            pricePerDay: 180,
            deposit: 600,
            availability: 'available',
            features: 'Sonnendeck, Kühlbox'
        },
        {
            id: 3,
            name: 'Meeresbrise',
            type: 'segelboot',
            icon: '⛵',
            capacity: 4,
            length: '6.8m',
            pricePerDay: 95,
            deposit: 400,
            availability: 'available',
            features: 'Kajüte, Navigation'
        },
        {
            id: 4,
            name: 'Forelle',
            type: 'kajak',
            icon: '🛶',
            capacity: 2,
            length: '4.2m',
            pricePerDay: 35,
            deposit: 100,
            availability: 'available',
            features: 'Schwimmwesten inkl.'
        },
        {
            id: 5,
            name: 'Neptun Express',
            type: 'motorboot',
            icon: '🚤',
            capacity: 10,
            length: '9.5m',
            pricePerDay: 250,
            deposit: 800,
            availability: 'limited',
            features: 'Kabine, Grill, Radio'
        },
        {
            id: 6,
            name: 'Seeadler',
            type: 'segelboot',
            icon: '⛵',
            capacity: 8,
            length: '10.2m',
            pricePerDay: 200,
            deposit: 700,
            availability: 'available',
            features: '2 Kajüten, Küche'
        },
        {
            id: 7,
            name: 'Waldbach',
            type: 'kanu',
            icon: '🛶',
            capacity: 3,
            length: '4.8m',
            pricePerDay: 40,
            deposit: 80,
            availability: 'available',
            features: 'Paddel inkl.'
        },
        {
            id: 8,
            name: 'Sunset Cruiser',
            type: 'motorboot',
            icon: '🚤',
            capacity: 6,
            length: '6.5m',
            pricePerDay: 150,
            deposit: 550,
            availability: 'available',
            features: 'Musikanlage, Kühlbox'
        },
        {
            id: 9,
            name: 'Wave Rider',
            type: 'sup',
            icon: '🏄',
            capacity: 1,
            length: '3.2m',
            pricePerDay: 25,
            deposit: 50,
            availability: 'available',
            features: 'Paddel, Pumpe'
        },
        {
            id: 10,
            name: 'Lake Explorer',
            type: 'kajak',
            icon: '🛶',
            capacity: 2,
            length: '4.5m',
            pricePerDay: 38,
            deposit: 100,
            availability: 'limited',
            features: 'Wasserdicht, GPS'
        },
        {
            id: 11,
            name: 'Ocean Breeze',
            type: 'sup',
            icon: '🏄',
            capacity: 1,
            length: '3.4m',
            pricePerDay: 28,
            deposit: 50,
            availability: 'available',
            features: 'Paddel, Tasche'
        },
        {
            id: 12,
            name: 'Familie Plus',
            type: 'kanu',
            icon: '🛶',
            capacity: 4,
            length: '5.2m',
            pricePerDay: 45,
            deposit: 120,
            availability: 'available',
            features: 'Stabil, geräumig'
        }
    ];

    let currentBoats = [...boats];
    let selectedBoat = null;

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('booking-start-date').setAttribute('min', today);

    function renderBoats() {
        const grid = document.getElementById('boatsGrid');
        grid.innerHTML = '';

        currentBoats.forEach(boat => {
            const boatCard = document.createElement('div');
            boatCard.className = 'boat-card';
            boatCard.innerHTML = `
                <div class="boat-image">${boat.icon}</div>
                <div class="boat-content">
                    <div class="boat-header">
                        <h3 class="boat-name">${boat.name}</h3>
                        <p class="boat-type">${getTypeLabel(boat.type)}</p>
                    </div>

                    <span class="availability-badge ${boat.availability}">
                        ${boat.availability === 'available' ? 'Verfügbar' : 'Begrenzt verfügbar'}
                    </span>

                    <div class="boat-specs">
                        <div class="spec-item">
                            <span class="spec-label">Kapazität</span>
                            ${boat.capacity} Personen
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Länge</span>
                            ${boat.length}
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Kaution</span>
                            ${boat.deposit},00 €
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Features</span>
                            ${boat.features}
                        </div>
                    </div>

                    <div class="boat-pricing">
                        <span class="price">${boat.pricePerDay},00 €</span>
                        <span class="price-period">/ Tag</span>
                    </div>

                    <button class="btn-rent" onclick="openBookingModal(${boat.id})">
                        Jetzt buchen
                    </button>
                </div>
            `;
            grid.appendChild(boatCard);
        });
    }

    function getTypeLabel(type) {
        const labels = {
            'segelboot': 'Segelboot',
            'motorboot': 'Motorboot',
            'kajak': 'Kajak',
            'kanu': 'Kanu',
            'sup': 'Stand-Up-Paddle'
        };
        return labels[type] || type;
    }

    function sortBoats() {
        const sortBy = document.getElementById('sortBy').value;

        switch(sortBy) {
            case 'name':
                currentBoats.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'price-low':
                currentBoats.sort((a, b) => a.pricePerDay - b.pricePerDay);
                break;
            case 'price-high':
                currentBoats.sort((a, b) => b.pricePerDay - a.pricePerDay);
                break;
            case 'capacity':
                currentBoats.sort((a, b) => b.capacity - a.capacity);
                break;
            case 'type':
                currentBoats.sort((a, b) => a.type.localeCompare(b.type));
                break;
        }

        renderBoats();
    }

    function filterBoats() {
        const type = document.getElementById('filterType').value;
        const capacity = parseInt(document.getElementById('filterCapacity').value);

        currentBoats = boats.filter(boat => {
            const typeMatch = type === 'all' || boat.type === type;
            const capacityMatch = capacity === 0 || boat.capacity >= capacity;
            return typeMatch && capacityMatch;
        });

        sortBoats();
    }

    function openBookingModal(boatId) {
        selectedBoat = boats.find(b => b.id === boatId);
        document.getElementById('modal-boat-name').textContent = selectedBoat.name;
        document.getElementById('booking-boat-id').value = boatId;
        document.getElementById('cost-daily').textContent = selectedBoat.pricePerDay.toFixed(2).replace('.', ',') + ' €';
        document.getElementById('cost-deposit').textContent = selectedBoat.deposit.toFixed(2).replace('.', ',') + ' €';

        // Reset form
        document.getElementById('bookingForm').reset();
        document.getElementById('cost-duration').textContent = '-';
        document.getElementById('cost-total').textContent = '0,00 €';

        document.getElementById('bookingModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('bookingModal').style.display = 'none';
        selectedBoat = null;
    }

    function calculateCost() {
        if (!selectedBoat) return;

        const duration = parseFloat(document.getElementById('booking-duration').value);
        if (!duration) return;

        const dailyRate = selectedBoat.pricePerDay;
        const deposit = selectedBoat.deposit;

        let totalDays = duration;
        let durationText = '';

        if (duration === 0.5) {
            durationText = 'Halbtag (4 Stunden)';
        } else if (duration === 1) {
            durationText = '1 Tag';
        } else if (duration < 7) {
            durationText = duration + ' Tage';
        } else if (duration === 7) {
            durationText = '1 Woche';
        } else if (duration === 14) {
            durationText = '2 Wochen';
        } else if (duration === 30) {
            durationText = '1 Monat';
        }

        const rentalCost = dailyRate * totalDays;
        const totalCost = rentalCost + deposit;

        document.getElementById('cost-duration').textContent = durationText;
        document.getElementById('cost-total').textContent = totalCost.toFixed(2).replace('.', ',') + ' €';
    }

    function submitBooking(event) {
        event.preventDefault();

        const boatName = selectedBoat.name;
        const startDate = document.getElementById('booking-start-date').value;
        const duration = document.getElementById('booking-duration').options[document.getElementById('booking-duration').selectedIndex].text;
        const name = document.getElementById('booking-name').value;
        const totalCost = document.getElementById('cost-total').textContent;

        alert(`Buchung erfolgreich!\n\nBoot: ${boatName}\nName: ${name}\nStartdatum: ${startDate}\nDauer: ${duration}\nGesamtpreis: ${totalCost}\n\nSie erhalten eine Bestätigung per E-Mail.`);

        closeModal();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == document.getElementById('bookingModal')) {
            closeModal();
        }
    }

    // Initial render
    renderBoats();
</script>

</body>
</html>
