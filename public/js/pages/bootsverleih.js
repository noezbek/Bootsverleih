import {apiGetMany, apiPostFormData, toFormData} from "../services/api.js";
import {setText, setValue, val, escape, formatEuro, isEmpty} from "../services/helpers.js";

let boote = {};
let featuresState = {};
let bootMieten = {};
let currentIds = [];
let selectedBootId = null;

export async function init() {
    wireModalClose();
    wireFilters();
    wireGridActions();
    wireBookingForm();
    applyDarkMode();
    setMinDateToday();

    await initialLoad();
}

async function initialLoad() {

    const {booteData, featuresData, boatRents} = await apiGetMany({
        boatRents: '/bootsverleih/loadMieten',
        booteData: '/bootsverleih/load',
        featuresData: '/features/load',
    })

    boote = booteData ?? {};
    featuresState = featuresData ?? {};
    bootMieten = boatRents ?? {};
    currentIds = Object.keys(boote).map(Number);

    applyFilterAndSort();
}

function renderGrid() {
    const grid = document.getElementById('boatsGrid');
    if (!grid) return;

    grid.innerHTML = '';

    currentIds.forEach(id => {
        const b = boote[id];
        if (!b) return;
        grid.appendChild(buildCard(id, b));
    });

    if (currentIds.length === 0) {
        grid.innerHTML = `<div style="padding:20px;">Keine Boote gefunden</div>`;
    }
}

function getMietbar(boatID, startDate, endDate) {
    const reservations = bootMieten[boatID];

    if (isEmpty(reservations)) return 'Verfügbar';

    for (let r of reservations) {
        if (r.startdatum <= endDate && r.enddatum >= startDate) {
            return 'Vermietet'
        }
    }
    return 'Verfügbar'
}


function buildCard(id, b) {
    const card = document.createElement('div');
    card.className = 'boat-card';
    card.dataset.boatId = String(id);

    const isBooked = getMietbar(id, new Date().toISOString().split('T')[0], new Date().toISOString().split('T')[0])

    const availabilityText = isBooked;
    const availabilityClass = isBooked !== 'Verfügbar' ? 'limited' : 'available';

    card.innerHTML = `
        <div class="boat-image">${escape(b.icon ?? '⛵')}</div>
        <div class="boat-content">
            <div class="boat-header">
                <h3 class="boat-name">${escape(b.name ?? '')}</h3>
                <p class="boat-type">${escape(typeLabel(b.type))}</p>
            </div>

            <span class="availability-badge ${availabilityClass}">
                ${availabilityText}
            </span>

            <div class="boat-specs">
                <div class="spec-item">
                    <span class="spec-label">Kapazität</span>
                    ${escape(b.capacity)} Personen
                </div>
                <div class="spec-item">
                    <span class="spec-label">Länge</span>
                    ${escape(b.length)}
                </div>
                <div class="spec-item">
                    <span class="spec-label">Kaution</span>
                    ${escape(b.deposit)},00 €
                </div>
                <div class="spec-item">
                    <span class="spec-label">Features</span>
                    ${b.features.map(id => featuresState[id]?.bezeichnung).join(', ')}
                </div>
            </div>

            <div class="boat-pricing">
                <span class="price">${escape(b.pricePerDay)},00 €</span>
                <span class="price-period">/ Tag</span>
            </div>

            <button class="btn-rent" data-action="book">Jetzt buchen</button>
        </div>
    `;

    return card;
}

function wireFilters() {
    document.getElementById('sortBy')?.addEventListener('change', applyFilterAndSort);
    document.getElementById('filterType')?.addEventListener('change', applyFilterAndSort);
    document.getElementById('filterCapacity')?.addEventListener('change', applyFilterAndSort);

    document.getElementById('booking-start-date')?.addEventListener('change', calculateCost);
    document.getElementById('booking-end-date')?.addEventListener('change', calculateCost);

}

function applyFilterAndSort() {
    const type = document.getElementById('filterType')?.value ?? 'all';
    const cap = Number(document.getElementById('filterCapacity')?.value ?? 0);
    const sortBy = document.getElementById('sortBy')?.value ?? 'name';

    // 1) filtern → ids
    let ids = Object.keys(boote).map(Number);

    ids = ids.filter(id => {
        const b = boote[id];
        if (!b) return false;

        const typeMatch = type === 'all' || b.type === type;
        const capMatch = cap === 0 || Number(b.capacity ?? 0) >= cap;

        return typeMatch && capMatch;
    });

    // 2) sortieren → ids
    ids.sort((a, b) => compareBoots(sortBy, boote[a], boote[b]));

    currentIds = ids;
    renderGrid();
}

function compareBoots(sortBy, a, b) {
    if (!a || !b) return 0;

    switch (sortBy) {
        case 'name':
            return String(a.name ?? '').localeCompare(String(b.name ?? ''));
        case 'price-low':
            return Number(a.pricePerDay ?? 0) - Number(b.pricePerDay ?? 0);
        case 'price-high':
            return Number(b.pricePerDay ?? 0) - Number(a.pricePerDay ?? 0);
        case 'capacity':
            return Number(b.capacity ?? 0) - Number(a.capacity ?? 0);
        case 'type':
            return String(a.type ?? '').localeCompare(String(b.type ?? ''));
        default:
            return 0;
    }
}

function wireGridActions() {
    document.getElementById('boatsGrid')
        ?.addEventListener('click', e => {
            const btn = e.target.closest('button');
            if (!btn) return;

            const card = e.target.closest('.boat-card');
            const id = Number(card?.dataset.boatId);
            if (!id) return;

            if (btn.dataset.action === 'book') openBookingModal(id);
        });
}

function openBookingModal(id) {
    const b = boote[id];
    if (!b) return;

    selectedBootId = id;

    setText('modal-boat-name', b.name ?? 'Boot buchen');
    setValue('booking-boat-id', id);

    setText('cost-daily', formatEuro(b.pricePerDay));
    setText('cost-deposit', formatEuro(b.deposit));
    setText('cost-duration', '–');   // Anzeige-Platzhalter
    setText('cost-total', '0,00 €');

    // Form reset (safe)
    const form = document.getElementById('bookingForm');
    form?.reset();

    // Optional, aber sinnvoll: Start = heute, Ende = morgen
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);

    const start = document.getElementById('booking-start-date');
    const end   = document.getElementById('booking-end-date');

    if (start) start.value = today.toISOString().split('T')[0];
    if (end)   end.value  = tomorrow.toISOString().split('T')[0];

    calculateCost(); // setzt cost-duration & cost-total korrekt

    document.getElementById('bookingModal').style.display = 'block';
}


function calculateCost() {
    if (!selectedBootId) return;
    const b = boote[selectedBootId];
    if (!b) return;

    const start = val('booking-start-date');
    const end = val('booking-end-date');
    if (!start || !end) return;

    const startDate = new Date(start);
    const endDate = new Date(end);

    const diffMs = endDate - startDate;
    if (diffMs <= 0) return;

    const days = Math.ceil(diffMs / (1000 * 60 * 60 * 24));

    const rentalCost = days * Number(b.pricePerDay ?? 0);
    const total = rentalCost + Number(b.deposit ?? 0);

    setText('cost-duration', `${days} Tage`);
    setText('cost-total', formatEuro(total));
}


async function wireBookingForm() {
    document.getElementById('bookingForm')
        ?.addEventListener('submit', async e => {
            e.preventDefault();

            const b = boote[selectedBootId];
            if (!b) return;

            const startDate = val('booking-start-date') ?? '';
            const endDate = val('booking-end-date') ?? '';

            const name = val('booking-name') ?? '';
            const total = document.getElementById('cost-total')?.textContent ?? '0,00 €';

            const data = {
                bootID: selectedBootId,
                startDate,
                endDate,
                preisProTag: b.pricePerDay
            }

            const res = await apiPostFormData('/bootsverleih/mieten', toFormData(data));

            console.log('res', res);

            if (res) {
                alert(
                    `Buchung erfolgreich!\n\n` +
                    `Boot: ${b.name}\n` +
                    `Name: ${name}\n` +
                    `Startdatum: ${startDate}\n` +
                    `Enddatum: ${endDate}\n` +
                    `Gesamtpreis: ${total}\n\n` +
                    `Sie erhalten eine Bestätigung per E-Mail.`
                );

                closeModal('bookingModal');
            }
        });
}

function wireModalClose() {
    // wie kundenverwaltung: [data-close]
    document.querySelectorAll('.close[data-close], [data-close]').forEach(el =>
        el.addEventListener('click', () => closeModal(el.dataset.close))
    );

    window.addEventListener('click', e => {
        if (e.target.classList.contains('modal')) e.target.style.display = 'none';
    });
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
    selectedBootId = null;
}

function applyDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}

function setMinDateToday() {
    const today = new Date().toISOString().split('T')[0];

    const start = document.getElementById('booking-start-date');
    const end   = document.getElementById('booking-end-date');

    if (start) start.setAttribute('min', today);
    if (end)   end.setAttribute('min', today);
}

function typeLabel(type) {
    const labels = {
        segelboot: 'Segelboot',
        motorboot: 'Motorboot',
        kajak: 'Kajak',
        kanu: 'Kanu',
        sup: 'SUP',
    };
    return labels[type] ?? type ?? '';
}