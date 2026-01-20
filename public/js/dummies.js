export const dummyCustomers = {
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

const dummyBoats = [
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