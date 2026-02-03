import {apiGetMany, apiPostFormData, toFormData} from '../services/api.js';
import {setText, setValue, val, escape, isEmpty} from "../services/helpers.js";

let liegeplaetze = {};

export async function init() {
    wireModalClose();
    wireSearch();
    wireSaveButton();
    wireTableActions();
    applyDarkMode();
    await initialLoad();
}

async function initialLoad() {
    const {berths} = await apiGetMany({
        berths: '/liegeplaetze/load',
    });

    liegeplaetze = berths ?? {};
    renderFullTable();
}

function renderFullTable() {
    const tbody = document.querySelector('#berthsTable tbody');
    tbody.innerHTML = '';

    Object.entries(liegeplaetze).forEach(([id, lp]) => {
        tbody.appendChild(buildRow(id, lp));
    });

    if (Object.keys(liegeplaetze).length === 0) {
        tbody.innerHTML = `<tr><td colspan="8">Keine Liegeplätze vorhanden</td></tr>`;
    }
}

function renderOrUpdateRow(id) {
    const tbody = document.querySelector('#berthsTable tbody');
    const existing = tbody.querySelector(`tr[data-berth-id="${id}"]`);

    const row = buildRow(id, liegeplaetze[id]);

    if (existing) {
        existing.replaceWith(row);
    } else {
        tbody.appendChild(row);
    }
}

function buildRow(id, lp) {
    const tr = document.createElement('tr');
    tr.dataset.berthId = id;

    const active = lp.active === true || lp.active === 1;
    const reservationCount = lp.reservierungen?.length ?? 0;

    tr.innerHTML = `
        <td>${escape(id)}</td>
        <td class="item-name">${escape(lp.bezeichnung ?? lp.name ?? '-')}</td>
        <td>${escape(lp.name ?? lp.bezeichnung ?? '-')}</td>
        <td>${lp.capacity ?? '-'} m</td>
        <td>${lp.pricePerDay ? Number(lp.pricePerDay).toFixed(2) + ' EUR' : '-'}</td>
        <td>
            <span class="reservation-count">${reservationCount}</span>
        </td>
        <td>
            <span class="status-badge ${active ? 'status-active' : 'status-inactive'}">
                ${active ? 'Aktiv' : 'Inaktiv'}
            </span>
        </td>
        <td>
            <div class="action-buttons">
                <button class="btn-small btn-view">Details</button>
                <button class="btn-small btn-edit">Bearbeiten</button>
            </div>
        </td>
    `;

    return tr;
}

function wireSearch() {
    const input = document.getElementById('searchInput');
    if (!input) return;

    input.addEventListener('keyup', () => {
        const q = input.value.toLowerCase();
        document.querySelectorAll('#berthsTable tbody tr')
            .forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
    });
}

function wireTableActions() {
    document.querySelector('#berthsTable tbody')
        .addEventListener('click', e => {
            const btn = e.target.closest('button');
            if (!btn) return;

            const id = btn.closest('tr')?.dataset.berthId;
            if (!id) return;

            if (btn.classList.contains('btn-view')) openViewModal(id);
            if (btn.classList.contains('btn-edit')) openEditModal(id);
        });
}

function openViewModal(id) {
    const lp = liegeplaetze[id];
    if (!lp) return;

    setText('view-berthId', id);
    setText('view-bezeichnung', lp.bezeichnung ?? '-');
    setText('view-beschreibung', lp.name ?? '-');
    setText('view-kapazitaet', lp.capacity ? `${lp.capacity} m` : '-');
    setText('view-preis', lp.pricePerDay ? `${Number(lp.pricePerDay).toFixed(2)} EUR` : '-');
    setText('view-status', (lp.active === true || lp.active === 1) ? 'Aktiv' : 'Inaktiv');

    renderReservations(lp.reservierungen ?? []);

    document.getElementById('viewModal').style.display = 'block';
}

function renderReservations(reservierungen) {
    const container = document.getElementById('view-reservations');
    container.innerHTML = '';

    if (!reservierungen.length) {
        container.innerHTML = '<div class="empty-state">Keine Reservierungen vorhanden</div>';
        return;
    }

    reservierungen.forEach(r => {
        const statusText = getReservationStatusText(r.status);
        const statusClass = getReservationStatusClass(r.status);

        const div = document.createElement('div');
        div.className = 'reservation-item';
        div.innerHTML = `
            <div class="reservation-info">
                <div class="reservation-dates">${r.startdatum ?? '-'} bis ${r.enddatum ?? '-'}</div>
                <span class="reservation-status ${statusClass}">${statusText}</span>
            </div>
            <div class="action-buttons">
                <button class="btn-small btn-edit" data-reservation-id="${r.id}">Bearbeiten</button>
            </div>
        `;
        container.appendChild(div);
    });
}

function getReservationStatusText(status) {
    switch (status) {
        case 1: return 'Angefragt';
        case 2: return 'Reserviert';
        case 3: return 'Storniert';
        default: return 'Unbekannt';
    }
}

function getReservationStatusClass(status) {
    switch (status) {
        case 1: return 'angefragt';
        case 2: return 'reserviert';
        case 3: return 'storniert';
        default: return '';
    }
}

function openEditModal(id) {
    const lp = liegeplaetze[id];
    if (!lp) return;

    document.getElementById('editModalTitle').textContent = 'Liegeplatz bearbeiten';

    setValue('edit-berthId', id);
    setValue('edit-bezeichnung', lp.bezeichnung ?? '');
    setValue('edit-beschreibung', lp.name ?? '');
    setValue('edit-kapazitaet', lp.capacity ?? '');
    setValue('edit-preis', lp.pricePerDay ?? '');
    setValue('edit-status', (lp.active === true || lp.active === 1) ? '1' : '0');

    document.getElementById('editModal').style.display = 'block';
}

function wireSaveButton() {
    document.getElementById('btnSaveBerth')
        ?.addEventListener('click', doSave);

    document.getElementById('btnCancelEdit')
        ?.addEventListener('click', () => closeModal('editModal'));
}

async function doSave() {
    const rawActive = val('edit-status');

    const payload = {
        id: val('edit-berthId') || null,
        bezeichnung: val('edit-bezeichnung') || null,
        beschreibung: val('edit-beschreibung') || null,
        kapazitaet: val('edit-kapazitaet') || null,
        preis_pro_tag: val('edit-preis') || null,
        active: isEmpty(rawActive) ? 1 : rawActive,
    };

    const fd = toFormData(payload, 'data');

    // Note: You may need to create a save endpoint for liegeplaetze
    // For now this uses a placeholder - implement the backend route as needed
    try {
        const saved = await apiPostFormData('/liegeplaetze/save', fd);
        liegeplaetze[saved.id] = saved;
        renderOrUpdateRow(saved.id);
        closeModal('editModal');
    } catch (e) {
        console.error('Speichern fehlgeschlagen:', e);
        alert('Speichern fehlgeschlagen. Bitte Backend-Route implementieren.');
    }
}

function wireModalClose() {
    document.querySelectorAll('.close[data-close]').forEach(el =>
        el.addEventListener('click', () => closeModal(el.dataset.close))
    );

    window.addEventListener('click', e => {
        if (e.target.classList.contains('modal')) e.target.style.display = 'none';
    });
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
    }
}

function applyDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}
