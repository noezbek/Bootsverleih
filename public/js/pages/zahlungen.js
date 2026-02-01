// ==============================
// IMPORT
// ==============================
import {apiGet, apiGetMany} from "../services/api.js";


let payments = [];
let historyRows = [];
let boats = {};
let berths = {};

export const ITEM_TYPE_BOAT_CONST = 'boot';
export const ITEM_TYPE_BERTH_CONST = 'liegeplatz';

// ==============================
// INIT
// ==============================
export async function init() {
    applyDarkMode();
    await initialLoad();
}

async function initialLoad() {
    try {
        const {zahlungen, boote, liegeplaetze} = await apiGetMany({
            zahlungen: '/zahlungen/load',
            boote: '/bootsverleih/load',
            liegeplaetze: '/liegeplaetze/load',
        })

        if (Array.isArray(zahlungen) && zahlungen.length) {
            boats = boote;
            berths = liegeplaetze;
            const mapped = mapApiPayments(zahlungen);
            payments = mapped.payments;
            historyRows = mapped.history;
        } else {
            payments = [];
            historyRows = [];
        }
    } catch (e) {
        payments = [];
        historyRows = [];
    }

    renderPayments();
    renderHistory();
}

// ==============================
// API → VIEW MAPPING
// ==============================
function mapApiPayments(apiData) {
    const cards = [];
    const history = [];

    apiData.forEach(entry => {
        const { item, itemType, vertrag, zahlungen } = entry;
        if (!vertrag || !Array.isArray(zahlungen)) return;

        const relevant = getRelevantPayment(zahlungen);

        const entity = getItem(item, itemType);

        zahlungen.forEach(z => {
            history.push({
                date: formatDate(z.bezahltAm ?? z.faelligAm),
                title: entity?.name ?? '-',
                price: formatPrice(z.betrag),
                status: z.bezahltAm ? 'Bezahlt' : 'Ausstehend',
                invoice: '-'
            });
        });

        cards.push({
            id: `V-${vertrag.id}`,
            title: entity?.name ?? '-',
            type: getIntervalLabel(vertrag.zahlungsrhythmus),
            status: relevant?.bezahltAm ? 'active' : 'pending',
            nextText: relevant
                ? `${daysUntil(relevant.faelligAm)} Tage (${formatDate(relevant.faelligAm)})`
                : '-',
            price: relevant ? formatPrice(relevant.betrag) : '-',
            interval: getIntervalLabel(vertrag.zahlungsrhythmus),
            start: formatDate(vertrag.vertragsbeginn),
            method: getMethodLabel(vertrag.zahlungsmethode),
        });
    });

    return { payments: cards, history };
}

function getRelevantPayment(payments = []) {
    return payments.filter(p => !p.bezahltAm).sort((a, b) => new Date(a.faelligAm) - new Date(b.faelligAm))[0]
        ?? payments.filter(p => p.bezahltAm).sort((a, b) => new Date(b.bezahltAm) - new Date(a.bezahltAm))[0]
        ?? null;
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
            </div>
        `);
    });
}

function renderHistory() {
    const tbody = document.getElementById('paymentHistoryBody');
    tbody.innerHTML = '';

    historyRows.forEach(h => {
        tbody.insertAdjacentHTML('beforeend', `
            <tr>
                <td>${h.date}</td>
                <td>${h.title}</td>
                <td>${h.price}</td>
                <td>${h.status}</td>
                <td><a href="#">PDF</a></td>
            </tr>
        `);
    });
}

function getIntervalLabel(v) {
    const { zahlRhythmus } = window.APP.enums;
    return zahlRhythmus[v] ?? '-';
}

function getMethodLabel(v) {
    const { zahlMethoden } = window.APP.enums;
    return zahlMethoden[v] ?? '-';
}

function applyDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}

function formatDate(d) {
    return new Date(d).toLocaleDateString('de-DE');
}

function daysUntil(d) {
    return Math.ceil((new Date(d) - new Date()) / 86400000);
}

function formatPrice(v) {
    return v.toLocaleString('de-DE', { minimumFractionDigits: 2 }) + ' €';
}

function getItem(item, itemType) {
    switch (itemType) {
        case ITEM_TYPE_BOAT_CONST:
            return boats[item.boot];
        case ITEM_TYPE_BERTH_CONST:
            return berths[item.liegeplatz];
        default:
            return null;
    }
}