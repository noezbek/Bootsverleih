import {apiGetMany, apiPostFormData, toFormData} from '../services/api.js';
import {setText, setValue, val, escape, isEmpty} from "../services/helpers.js";

let kunden = {};
let zahlungen = {};
let bestellungen = {};
let vertraege = {};

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

    const {clients, payments, contracts, orders} = await apiGetMany({
        clients: '/kundenverwaltung/load',
        payments: '/zahlungen/load',
        contracts: '/vertraege/load',
        orders: '/bestellungen/load',
    })

    kunden = clients ?? {};
    zahlungen = payments ?? {};
    bestellungen = orders ?? {};
    vertraege = contracts ?? {};

    renderFullTable();
}

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

    renderPayments(k.zahlungen);
    renderOrders(k.bestellungen);

    document.getElementById('viewModal').style.display = 'block';
}

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
    setValue('edit-geburtsdatum', k?.geburtsdatum ?? null);

    const statusGroup = document.getElementById('edit-status')?.closest('.form-group');

    if (isNew) {
        statusGroup.style.display = 'none';
        setValue('edit-status', 1); // neuer Kunde immer aktiv
    } else {
        statusGroup.style.display = '';
        setValue('edit-status', String(k?.active === true || k?.active === 1 ? '1' : '0'));

    }

    document.getElementById('editModal').style.display = 'block';
}

function renderPayments(zahlungsIds = []) {
    const container = document.getElementById('view-payments');
    container.innerHTML = '';

    if (!zahlungsIds.length) {
        container.innerHTML =
            '<div class="history-item">Noch keine Zahlungsdaten</div>';
        return;
    }

    zahlungsIds.forEach(id => {
        const z = zahlungen[id];

        console.log('zahlungen',z);

        if (!z) return;

        container.innerHTML += `
            <div class="history-item">
                <span class="date">${z.datum ?? '-'}</span>
                <span class="amount">${Number(z.betrag).toFixed(2)} €</span>
                <span class="status ${z.status === 'bezahlt' ? 'paid' : 'open'}">
                    ${z.status}
                </span>
            </div>
        `;
    });
}

function renderOrders(bestellIds = []) {
    const container = document.getElementById('view-orders');
    container.innerHTML = '';

    if (!bestellIds.length) {
        container.innerHTML =
            '<div class="history-item">Noch keine Bestellhistorie</div>';
        return;
    }

    bestellIds.forEach(id => {
        const b = bestellungen[id];

        console.log('bestellungen',b);

        if (!b) return;

        // passenden Vertrag suchen (1 Bestellung → 1 Vertrag angenommen)
        const vertrag = Object.values(vertraege)
            .find(v => v.bestellung_ID === id);

        const zeitraum = vertrag
            ? `${vertrag.startdatum} – ${vertrag.enddatum}`
            : '—';

        container.innerHTML += `
            <div class="history-item">
                <span class="order-id">#${id}</span>
                <span class="period">${zeitraum}</span>
                <span class="status ${b.status === 1 ? 'active' : 'inactive'}">
                    ${b.status === 1 ? 'Aktiv' : 'Abgeschlossen'}
                </span>
            </div>
        `;
    });
}


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

async function doDelete(id) {
    if (!confirm('Kunden wirklich löschen?')) return;

    const fd = new FormData()
    fd.append('id', id);
    await apiPostFormData('/kundenverwaltung/delete', fd);

    delete kunden[id];
    removeRow(id);
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
