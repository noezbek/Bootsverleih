import {apiGetMany, apiPostFormData, toFormData} from '../services/api.js';
import {setText, setValue, val, escape, isEmpty} from "../services/helpers.js";
import {BOAT_TYPES, AVAILABILITY} from "../services/BoatConstants.js";

let boote = {};
let features = {};

export async function init() {
    wireModalClose();
    wireSearch();
    wireAddButton();
    wireSaveButton();
    wireTableActions();
    applyDarkMode();
    await initialLoad();
}

async function initialLoad() {
    const {boats, featureList} = await apiGetMany({
        boats: '/bootsverleih/load',
        featureList: '/features/load',
    });

    boote = boats ?? {};
    features = featureList ?? {};
    renderFullTable();
}

function renderFullTable() {
    const tbody = document.querySelector('#boatsTable tbody');
    tbody.innerHTML = '';

    Object.entries(boote).forEach(([id, boot]) => {
        tbody.appendChild(buildRow(id, boot));
    });

    if (Object.keys(boote).length === 0) {
        tbody.innerHTML = `<tr><td colspan="9">Keine Boote vorhanden</td></tr>`;
    }
}

function renderOrUpdateRow(id) {
    const tbody = document.querySelector('#boatsTable tbody');
    const existing = tbody.querySelector(`tr[data-boat-id="${id}"]`);

    const row = buildRow(id, boote[id]);

    if (existing) {
        existing.replaceWith(row);
    } else {
        tbody.appendChild(row);
    }
}

function removeRow(id) {
    document
        .querySelector(`tr[data-boat-id="${id}"]`)
        ?.remove();
}

function getBoatTypeName(typeId) {
    return BOAT_TYPES[typeId] ?? 'Unbekannt';
}

function getAvailabilityName(availId) {
    return AVAILABILITY[availId] ?? 'Unbekannt';
}

function getAvailabilityClass(availId) {
    switch (parseInt(availId)) {
        case 1: return 'availability-available';
        case 2: return 'availability-reserved';
        case 3: return 'availability-maintenance';
        case 4: return 'availability-out';
        default: return '';
    }
}

function buildRow(id, boot) {
    const tr = document.createElement('tr');
    tr.dataset.boatId = id;

    const active = boot.active === true || boot.active === 1;

    tr.innerHTML = `
        <td>${escape(id)}</td>
        <td class="item-name">${escape(boot.name ?? '-')}</td>
        <td>${getBoatTypeName(boot.type)}</td>
        <td>${boot.capacity ?? '-'} Pers.</td>
        <td>${boot.pricePerDay ? Number(boot.pricePerDay).toFixed(2) + ' EUR' : '-'}</td>
        <td>${boot.deposit ? Number(boot.deposit).toFixed(2) + ' EUR' : '-'}</td>
        <td>
            <span class="availability-badge ${getAvailabilityClass(boot.availability)}">
                ${getAvailabilityName(boot.availability)}
            </span>
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
                <button class="btn-small btn-delete">Löschen</button>
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
        document.querySelectorAll('#boatsTable tbody tr')
            .forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
    });
}

function wireTableActions() {
    document.querySelector('#boatsTable tbody')
        .addEventListener('click', e => {
            const btn = e.target.closest('button');
            if (!btn) return;

            const id = btn.closest('tr')?.dataset.boatId;
            if (!id) return;

            if (btn.classList.contains('btn-view')) openViewModal(id);
            if (btn.classList.contains('btn-edit')) openEditModal(id);
            if (btn.classList.contains('btn-delete')) doDelete(id);
        });
}

function openViewModal(id) {
    const boot = boote[id];
    if (!boot) return;

    setText('view-boatId', id);
    setText('view-name', boot.name ?? '-');
    setText('view-type', getBoatTypeName(boot.type));
    setText('view-capacity', boot.capacity ? `${boot.capacity} Personen` : '-');
    setText('view-length', boot.length ? `${boot.length} m` : '-');
    setText('view-width', boot.width ? `${boot.width} m` : '-');
    setText('view-depth', boot.depth ? `${boot.depth} m` : '-');
    setText('view-price', boot.pricePerDay ? `${Number(boot.pricePerDay).toFixed(2)} EUR` : '-');
    setText('view-deposit', boot.deposit ? `${Number(boot.deposit).toFixed(2)} EUR` : '-');

    renderFeatures(boot.features ?? []);

    document.getElementById('viewModal').style.display = 'block';
}

function renderFeatures(featureIds) {
    const container = document.getElementById('view-features');
    container.innerHTML = '';

    if (!featureIds.length) {
        container.innerHTML = '<div class="empty-state">Keine Features zugewiesen</div>';
        return;
    }

    featureIds.forEach(fid => {
        const feature = features[fid];
        if (!feature) return;

        const span = document.createElement('span');
        span.className = 'feature-tag';
        span.textContent = feature.name ?? feature.bezeichnung ?? `Feature ${fid}`;
        container.appendChild(span);
    });
}

function wireAddButton() {
    document.getElementById('btnAddBoat')
        ?.addEventListener('click', () => openEditModal(null, true));
}

function openEditModal(id, isNew = false) {
    const boot = isNew ? {} : boote[id];

    document.getElementById('editModalTitle').textContent =
        isNew ? 'Neues Boot' : 'Boot bearbeiten';

    setValue('edit-boatId', isNew ? '' : id);
    setValue('edit-name', boot?.name ?? '');
    setValue('edit-type', boot?.type ?? '');
    setValue('edit-capacity', boot?.capacity ?? '');
    setValue('edit-length', boot?.length ?? '');
    setValue('edit-width', boot?.width ?? '');
    setValue('edit-depth', boot?.depth ?? '');
    setValue('edit-price', boot?.pricePerDay ?? '');
    setValue('edit-deposit', boot?.deposit ?? '');
    setValue('edit-availability', boot?.availability ?? '1');
    setValue('edit-status', (boot?.active === true || boot?.active === 1) ? '1' : '0');

    document.getElementById('editModal').style.display = 'block';
}

function wireSaveButton() {
    document.getElementById('btnSaveBoat')
        ?.addEventListener('click', doSave);

    document.getElementById('btnCancelEdit')
        ?.addEventListener('click', () => closeModal('editModal'));
}

async function doSave() {
    const rawActive = val('edit-status');

    const payload = {
        id: val('edit-boatId') || null,
        beschreibung: val('edit-name') || null,
        bootstyp: val('edit-type') || null,
        kapazitaet: val('edit-capacity') || null,
        laenge: val('edit-length') || null,
        breite: val('edit-width') || null,
        tiefgang: val('edit-depth') || null,
        preis_pro_tag: val('edit-price') || null,
        kaution: val('edit-deposit') || null,
        verfuegbarkeit: val('edit-availability') || 1,
        active: isEmpty(rawActive) ? 1 : rawActive,
    };

    const fd = toFormData(payload, 'data');

    try {
        const saved = await apiPostFormData('/bootsverleih/save', fd);
        boote[saved.id] = saved;
        renderOrUpdateRow(saved.id);
        closeModal('editModal');
    } catch (e) {
        console.error('Speichern fehlgeschlagen:', e);
        alert('Speichern fehlgeschlagen: ' + e.message);
    }
}

async function doDelete(id) {
    if (!confirm('Boot wirklich löschen?')) return;

    const fd = new FormData();
    fd.append('id', id);

    try {
        await apiPostFormData('/bootsverleih/delete', fd);
        delete boote[id];
        removeRow(id);
    } catch (e) {
        console.error('Löschen fehlgeschlagen:', e);
        alert('Löschen fehlgeschlagen: ' + e.message);
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
