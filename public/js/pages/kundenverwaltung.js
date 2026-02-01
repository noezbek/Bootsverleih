import { apiGet, apiPostFormData, toFormData } from '../services/api.js';
import {setText, setValue, val, escape, isEmpty} from "../services/helpers.js";

let kunden = {}; // { id: kunde }

// ==============================
// INIT (nur 1x beim Laden)
// ==============================
export async function init() {
    wireModalClose();
    wireSearch();
    wireAddButton();
    wireSaveButton();
    wireTableActions();
    applyDarkMode();

    await initialLoad();
}

// ==============================
// INITIAL LOAD (EINMALIG)
// ==============================
async function initialLoad() {
    const data = await apiGet('/kundenverwaltung/load');
    kunden = data?.kunden ?? {};
    renderFullTable();
}

// ==============================
// RENDER
// ==============================
function renderFullTable() {
    const tbody = document.querySelector('#customersTable tbody');
    tbody.innerHTML = '';

    Object.entries(kunden).forEach(([id, kunde]) => {
        tbody.appendChild(buildRow(id, kunde));
    });

    if (Object.keys(kunden).length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">Keine Kunden vorhanden</td></tr>`;
    }
}

function renderOrUpdateRow(id) {
    const tbody = document.querySelector('#customersTable tbody');
    const existing = tbody.querySelector(`tr[data-customer-id="${id}"]`);

    const row = buildRow(id, kunden[id]);

    if (existing) {
        existing.replaceWith(row);
    } else {
        tbody.appendChild(row);
    }
}

function removeRow(id) {
    document
        .querySelector(`tr[data-customer-id="${id}"]`)
        ?.remove();
}

function buildRow(id, k) {
    const tr = document.createElement('tr');
    tr.dataset.customerId = id;

    const active = k.active === true || k.active === 1;

    tr.innerHTML = `
        <td>${escape(id)}</td>
        <td class="customer-name">${escape(`${k.vorname ?? ''} ${k.nachname ?? ''}`)}</td>
        <td>${escape(k.email)}</td>
        <td>${escape(k.telefon)}</td>
        <td>
            <span class="status-badge ${active ? 'status-active' : 'status-inactive'}">
                ${active ? 'Aktiv' : 'Inaktiv'}
            </span>
        </td>
        <td>${escape(k.geburtsdatum) || '-'}</td>
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

// ==============================
// SEARCH
// ==============================
function wireSearch() {
    const input = document.getElementById('searchInput');
    if (!input) return;

    input.addEventListener('keyup', () => {
        const q = input.value.toLowerCase();
        document.querySelectorAll('#customersTable tbody tr')
            .forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
    });
}

// ==============================
// TABLE ACTIONS
// ==============================
function wireTableActions() {
    document.querySelector('#customersTable tbody')
        .addEventListener('click', e => {
            const btn = e.target.closest('button');
            if (!btn) return;

            const id = btn.closest('tr')?.dataset.customerId;
            if (!id) return;

            if (btn.classList.contains('btn-view')) openViewModal(id);
            if (btn.classList.contains('btn-edit')) openEditModal(id);
            if (btn.classList.contains('btn-delete')) doDelete(id);
        });
}

// ==============================
// VIEW
// ==============================
function openViewModal(id) {
    const k = kunden[id];
    if (!k) return;

    setText('view-customerId', id);
    setText('view-name', `${k.vorname ?? ''} ${k.nachname ?? ''}`);
    setText('view-email', k.email);
    setText('view-phone', k.telefon);
    setText(
        'view-address',
        `${k.strasse ?? ''}, ${k.plz ?? ''} ${k.stadt ?? ''}`.replace(/^,\s*/, '') || '-'
    );
    setText('view-geburtsdatum', k.geburtsdatum ?? '-');

    document.getElementById('viewModal').style.display = 'block';
}

// ==============================
// EDIT
// ==============================
function wireAddButton() {
    document.getElementById('btnAddCustomer')
        ?.addEventListener('click', () => openEditModal(null, true));
}

function openEditModal(id, isNew = false) {
    const k = isNew ? {} : kunden[id];

    setValue('edit-customerId', isNew ? null : id);
    setValue('edit-firstName', k?.vorname);
    setValue('edit-lastName', k?.nachname);
    setValue('edit-email', k?.email);
    setValue('edit-phone', k?.telefon);
    setValue('edit-address', k?.strasse);
    setValue('edit-city', k?.stadt);
    setValue('edit-zip', k?.plz);
    setValue('edit-status', k?.active ?? 1);
    setValue('edit-geburtsdatum', k?.geburtsdatum ?? null);

    document.getElementById('editModal').style.display = 'block';
}

// ==============================
// SAVE (UPDATE LOKAL)
// ==============================
function wireSaveButton() {
    document.getElementById('btnSaveCustomer')
        ?.addEventListener('click', doSave);
}

async function doSave() {
    const rawActive = val('edit-status');

    const payload = {
        id: val('edit-customerId') || null,
        vorname: val('edit-firstName') || null,
        nachname: val('edit-lastName') || null,
        email: val('edit-email') || null,
        telefon: val('edit-phone') || null,
        strasse: val('edit-address') || null,
        stadt: val('edit-city') || null,
        plz: val('edit-zip') || null,
        geburtsdatum: val('edit-geburtsdatum') || null,

        active: isEmpty(rawActive) ? 1 : rawActive,
    };

    const fd = toFormData(payload, 'data');
    const saved = await apiPostFormData('/kundenverwaltung/save', fd);

    kunden[saved.id] = saved;
    renderOrUpdateRow(saved.id);

    closeModal('editModal');
}

// ==============================
// DELETE (LOKAL)
// ==============================
async function doDelete(id) {
    if (!confirm('Kunden wirklich löschen?')) return;

    const fd = new FormData()
    fd.append('id', id);
    await apiPostFormData('/kundenverwaltung/delete', fd);

    delete kunden[id];
    removeRow(id);
}

// ==============================
// MODALS
// ==============================
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


// ==============================
// HELPERS
// ==============================

function applyDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}
