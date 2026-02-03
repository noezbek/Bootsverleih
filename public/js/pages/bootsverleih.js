import {apiGet, apiGetMany} from "../services/api.js";
import {setText, setValue, val, escape, formatEuro} from "../services/helpers.js";

let boote = {};
let features = {};
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

    const {booteData, featuresData} = await apiGetMany({
        booteData: '/bootsverleih/load',
        featuresData: '/features/load',
    })

    boote = booteData ?? {};
    features = featuresData ?? {};
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

function buildCard(id, b) {
    const card = document.createElement('div');
    card.className = 'boat-card';
    card.dataset.boatId = String(id);

    const availabilityText = b.availability === 'available' ? 'Verfügbar' : 'Begrenzt verfügbar';
    const availabilityClass = b.availability === 'available' ? 'available' : 'limited';

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
                    ${escape(b.features)}
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

    document.getElementById('booking-duration')?.addEventListener('change', calculateCost);
    document.getElementById('booking-start-date')?.addEventListener('change', calculateCost);
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
    setText('cost-duration', '-');
    setText('cost-total', '0,00 €');

    // form reset (wie bei kundenverwaltung: safe)
    document.getElementById('bookingForm')?.reset();

    document.getElementById('bookingModal').style.display = 'block';
}

function calculateCost() {
    if (!selectedBootId) return;
    const b = boote[selectedBootId];
    if (!b) return;

    const durationRaw = document.getElementById('booking-duration')?.value ?? '';
    const duration = Number(durationRaw);
    if (!duration) return;

    const rentalCost = Number(b.pricePerDay ?? 0) * duration;
    const total = rentalCost + Number(b.deposit ?? 0);

    setText('cost-duration', durationText(duration));
    setText('cost-total', total.toFixed(2).replace('.', ',') + ' €');
}

function wireBookingForm() {
    document.getElementById('bookingForm')
        ?.addEventListener('submit', e => {
            e.preventDefault();

            const b = boote[selectedBootId];
            if (!b) return;

            const startDate = val('booking-start-date') ?? '';
            const durationTextUI = document.getElementById('booking-duration')
                ?.options[document.getElementById('booking-duration').selectedIndex]?.text ?? '';

            const name = val('booking-name') ?? '';
            const total = document.getElementById('cost-total')?.textContent ?? '0,00 €';

            alert(
                `Buchung erfolgreich!\n\n` +
                `Boot: ${b.name}\n` +
                `Name: ${name}\n` +
                `Startdatum: ${startDate}\n` +
                `Dauer: ${durationTextUI}\n` +
                `Gesamtpreis: ${total}\n\n` +
                `Sie erhalten eine Bestätigung per E-Mail.`
            );

            closeModal('bookingModal');
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
    const el = document.getElementById('booking-start-date');
    if (!el) return;
    const today = new Date().toISOString().split('T')[0];
    el.setAttribute('min', today);
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

function durationText(d) {
    if (d === 0.5) return 'Halbtag (4 Stunden)';
    if (d === 1) return '1 Tag';
    if (d < 7) return `${d} Tage`;
    if (d === 7) return '1 Woche';
    if (d === 14) return '2 Wochen';
    if (d === 30) return '1 Monat';
    return String(d);
}