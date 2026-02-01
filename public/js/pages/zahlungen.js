// ==============================
// DUMMY DATEN (wie vorher, nur als Variable)
// ==============================
import {apiGet} from "../services/api.js";

const payments = [
    {
        id: 'LP-B12',
        title: 'Liegeplatz B-12',
        type: 'Jährliches Abonnement',
        status: 'active',
        nextText: '23 Tage (05.02.2026)',
        price: '1.250,00 €',
        interval: 'Jährlich',
        start: '05.02.2024',
        method: 'Lastschrift',
    },
    {
        id: 'BV-WS01',
        title: 'Bootsverleih - Segelboot "Windspiel"',
        type: 'Monatliches Abonnement',
        status: 'active',
        nextText: '8 Tage (21.01.2026)',
        price: '350,00 €',
        interval: 'Monatlich',
        start: '21.06.2025',
        method: 'Kreditkarte',
    },
    {
        id: 'WL-45',
        title: 'Winterlager - Stellplatz 45',
        type: 'Einmalige Zahlung',
        status: 'pending',
        nextText: '2 Tage (15.01.2026)',
        price: '580,00 €',
        interval: 'Einmalig',
        start: '28.12.2025',
        method: 'Überweisung',
    }
];

const history = [
    ['21.12.2025', 'Bootsverleih - Segelboot "Windspiel"', '350,00 €', 'Bezahlt', 'INV-2025-234'],
    ['21.11.2025', 'Bootsverleih - Segelboot "Windspiel"', '350,00 €', 'Bezahlt', 'INV-2025-198'],
    ['05.02.2025', 'Liegeplatz B-12 (Jahresgebühr)', '1.250,00 €', 'Bezahlt', 'INV-2025-045'],
    ['15.01.2025', 'Winterlager - Stellplatz 45', '550,00 €', 'Bezahlt', 'INV-2025-012'],
];

let zahlungen = {};
let bestellungen = {};

export async function init() {
    applyDarkMode();
    await initialLoad();
}

async function initialLoad() {
    const data = await apiGet('/zahlungen/load');
    zahlungen = data ?? {};
    renderPayments();
    renderHistory();
}

function renderPayments() {
    const container = document.getElementById('paymentsContainer');
    container.innerHTML = '';

    payments.forEach(p => {
        container.insertAdjacentHTML('beforeend', `
            <div class="payment-card">
                <div class="payment-header">
                    <div class="payment-title">
                        <h2>${p.title}</h2>
                        <p class="payment-type">${p.type}</p>
                    </div>
                    <span class="payment-status status-${p.status}">
                        ${p.status === 'active' ? 'Aktiv' : 'Ausstehend'}
                    </span>
                </div>

                <div class="next-payment-highlight">
                    <p>Nächste Zahlung fällig in:</p>
                    <span class="countdown">${p.nextText}</span>
                </div>

                <div class="payment-details">
                    <div class="detail-item">
                        <span class="detail-label">Zahlungsbetrag</span>
                        <span class="detail-value price">${p.price}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Zahlungsrhythmus</span>
                        <span class="detail-value">${p.interval}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Vertragsbeginn</span>
                        <span class="detail-value">${p.start}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Zahlungsmethode</span>
                        <span class="detail-value">${p.method}</span>
                    </div>
                </div>

                <div class="payment-actions">
                    <button class="btn btn-primary">Zahlungsplan ändern</button>
                    <button class="btn btn-secondary">Rechnungen ansehen</button>
                    <button class="btn btn-danger">
                        ${p.status === 'active' ? 'Kündigen' : 'Stornieren'}
                    </button>
                </div>
            </div>
        `);
    });
}

function renderHistory() {
    const tbody = document.getElementById('paymentHistoryBody');
    tbody.innerHTML = '';

    history.forEach(h => {
        tbody.insertAdjacentHTML('beforeend', `
            <tr>
                <td>${h[0]}</td>
                <td>${h[1]}</td>
                <td>${h[2]}</td>
                <td>${h[3]}</td>
                <td><a href="#">PDF</a></td>
            </tr>
        `);
    });
}

// ==============================
// HELPERS
// ==============================
function applyDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}
