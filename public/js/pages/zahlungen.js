import {apiGetMany} from "../services/api.js";

let payments = [];
let historyRows = [];
let boats = {};
let berths = {};
let contracts = {};
let orders = {};

export const ITEM_TYPE_BOAT_CONST = 'boot';
export const ITEM_TYPE_BERTH_CONST = 'liegeplatz';

export async function init() {
    applyDarkMode();
    await initialLoad();
}

async function initialLoad() {
    try {
        const {zahlungen, boote, liegeplaetze, vertraege, bestellungen} = await apiGetMany({
            zahlungen: '/zahlungen/loadByKunde',
            boote: '/bootsverleih/load',
            vertraege: '/vertraege/load',
            bestellungen: '/bestellungen/load',
            liegeplaetze: '/liegeplaetze/load',
        })

        boats = boote;
        berths = liegeplaetze;
        contracts = vertraege;
        orders = bestellungen;
        const mapped = mapApiPayments(Object.values(zahlungen));
        console.log('mapped', mapped);
        payments = mapped.cards;
        historyRows = mapped.history;
    } catch (e) {
        payments = [];
        historyRows = [];
    }

    renderPayments();
    renderHistory();
}

function mapApiPayments(apiData) {
    const history = [];
    const byVertrag = {};

    apiData.forEach(z => {
        // History: alle Zahlungen
        history.push({
            date: formatDate(z.bezahltAm ?? z.faelligAm),
            title: getTitleByVertrag(z.vertrag),
            price: formatPrice(z.betrag),
            status: z.bezahltAm ? 'Bezahlt' : 'Ausstehend',
            invoice: '-'
        });

        if (!byVertrag[z.vertrag]) {
            byVertrag[z.vertrag] = [];
        }
        byVertrag[z.vertrag].push(z);
    });

    const cards = Object.entries(byVertrag)
        .map(([vertragId, payments]) => {
            const relevant = getRelevantPayment(payments);
            if (!relevant) return null;

            const vertrag = contracts[vertragId];

            if (!vertrag) return null;

            const title = getTitleByVertrag(vertragId);
            console.log(title);
            return {
                id: vertragId,
                title: title,
                type: getIntervalLabel(vertrag.zahlungsrhythmus),
                status: relevant.bezahltAm ? 'active' : 'pending',
                nextText: relevant.faelligAm
                    ? `${daysUntil(relevant.faelligAm)} Tage (${formatDate(relevant.faelligAm)})`
                    : '-',
                price: formatPrice(relevant.betrag),
                interval: getIntervalLabel(vertrag.zahlungsrhythmus),
                start: formatDate(vertrag.vertragsbeginn),
                method: getMethodLabel(vertrag.zahlungsmethode),
            };
        })
        .filter(Boolean);

    return { cards, history };
}



function getRelevantPayment(payments = []) {
    return payments
            .filter(p => !p.bezahltAm)
            .sort((a, b) => new Date(a.faelligAm) - new Date(b.faelligAm))[0]
        ?? payments
            .filter(p => p.bezahltAm)
            .sort((a, b) => new Date(b.bezahltAm) - new Date(a.bezahltAm))[0]
        ?? null;
}


function getTitleByVertrag(vertragId) {
    const vertrag = contracts[vertragId];
    if (!vertrag) return '-';

    const bestellung = orders[vertrag.bestellung];

    if (!bestellung) return '-';

    //  Bootmiete hat Vorrang
    if (bestellung.gemieteteBoote?.length) {
        const bootId = bestellung.gemieteteBoote[0].boot;
        return boats[bootId]?.name ?? 'Boot';
    }

    //  sonst Liegeplatz
    if (bestellung.reservierteLiegeplaetze?.length) {
        const lpId = bestellung.reservierteLiegeplaetze[0].liegeplatz;
        return berths[lpId]?.name ?? 'Liegeplatz';
    }

    return '-';
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