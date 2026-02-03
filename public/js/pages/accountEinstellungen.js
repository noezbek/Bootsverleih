import { apiGet, apiPostFormData, toFormData } from '../api.js';
import { setText, setValue, val } from '../helpers.js';

// State
let userData = null;

// ==============================
// INIT
// ==============================
export async function init() {
    applyDarkMode();
    wireModalClose();
    wireEditPersonalButton();
    wireChangeUsernameButton();
    wireChangePasswordButton();
    wireSaveButtons();

    await loadAccountData();
}

// ==============================
// LOAD DATA
// ==============================
async function loadAccountData() {
    try {
        const data = await apiGet('/accountEinstellungen/load');
        userData = data;
        renderAccountData();
    } catch (e) {
        console.error('Failed to load account data:', e);
        showMessage('Fehler beim Laden der Kontodaten.', 'error');
    }
}

function renderAccountData() {
    if (!userData) return;

    const { user, kunde } = userData;

    // Username
    setText('view-username', user?.username ?? '-');

    // Personal data from Kunde
    if (kunde) {
        setText('view-vorname', kunde.vorname ?? '-');
        setText('view-nachname', kunde.nachname ?? '-');
        setText('view-email', kunde.email ?? '-');
        setText('view-telefon', kunde.telefon ?? '-');
        setText('view-strasse', kunde.strasse ?? '-');
        setText('view-plz', kunde.plz ?? '-');
        setText('view-stadt', kunde.stadt ?? '-');
        setText('view-geburtsdatum', formatDate(kunde.geburtsdatum));
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    try {
        const date = new Date(dateStr);
        return date.toLocaleDateString('de-DE');
    } catch {
        return dateStr;
    }
}

// ==============================
// EDIT PERSONAL DATA
// ==============================
function wireEditPersonalButton() {
    document.getElementById('btnEditPersonal')
        ?.addEventListener('click', openEditPersonalModal);
}

function openEditPersonalModal() {
    const kunde = userData?.kunde ?? {};

    setValue('edit-vorname', kunde.vorname ?? '');
    setValue('edit-nachname', kunde.nachname ?? '');
    setValue('edit-email', kunde.email ?? '');
    setValue('edit-telefon', kunde.telefon ?? '');
    setValue('edit-strasse', kunde.strasse ?? '');
    setValue('edit-plz', kunde.plz ?? '');
    setValue('edit-stadt', kunde.stadt ?? '');
    setValue('edit-geburtsdatum', kunde.geburtsdatum ?? '');

    document.getElementById('editPersonalModal').style.display = 'block';
}

async function savePersonalData() {
    const payload = {
        vorname: val('edit-vorname'),
        nachname: val('edit-nachname'),
        email: val('edit-email'),
        telefon: val('edit-telefon'),
        strasse: val('edit-strasse'),
        plz: val('edit-plz'),
        stadt: val('edit-stadt'),
        geburtsdatum: val('edit-geburtsdatum'),
    };

    try {
        const fd = toFormData(payload, 'data');
        const result = await apiPostFormData('/accountEinstellungen/savePersonal', fd);

        if (result.success) {
            userData.kunde = result.kunde;
            renderAccountData();
            closeModal('editPersonalModal');
            showMessage('Persönliche Daten wurden gespeichert.', 'success');
        } else {
            showMessage(result.error || 'Fehler beim Speichern der Daten.', 'error');
        }
    } catch (e) {
        console.error('Failed to save personal data:', e);
        showMessage('Fehler beim Speichern der Daten.', 'error');
    }
}

// ==============================
// CHANGE USERNAME
// ==============================
function wireChangeUsernameButton() {
    document.getElementById('btnChangeUsername')
        ?.addEventListener('click', () => {
            clearModalInputs('changeUsernameModal');
            document.getElementById('changeUsernameModal').style.display = 'block';
        });
}

async function saveUsername() {
    const currentPassword = val('username-currentPassword');
    const newUsername = val('username-newUsername');

    if (!currentPassword || !newUsername) {
        showMessage('Bitte alle Felder ausfüllen.', 'error');
        return;
    }

    const payload = { currentPassword, newUsername };

    try {
        const fd = toFormData(payload, 'data');
        const result = await apiPostFormData('/accountEinstellungen/changeUsername', fd);

        if (result.success) {
            userData.user.username = newUsername;
            renderAccountData();
            closeModal('changeUsernameModal');
            showMessage('Benutzername wurde geändert.', 'success');
        } else {
            showMessage(result.error || 'Fehler beim Ändern des Benutzernamens.', 'error');
        }
    } catch (e) {
        console.error('Failed to change username:', e);
        showMessage('Fehler beim Ändern des Benutzernamens.', 'error');
    }
}

// ==============================
// CHANGE PASSWORD
// ==============================
function wireChangePasswordButton() {
    document.getElementById('btnChangePassword')
        ?.addEventListener('click', () => {
            clearModalInputs('changePasswordModal');
            document.getElementById('changePasswordModal').style.display = 'block';
        });
}

async function savePassword() {
    const currentPassword = val('password-currentPassword');
    const newPassword = val('password-newPassword');
    const confirmPassword = val('password-confirmPassword');

    if (!currentPassword || !newPassword || !confirmPassword) {
        showMessage('Bitte alle Felder ausfüllen.', 'error');
        return;
    }

    if (newPassword !== confirmPassword) {
        showMessage('Die neuen Passwörter stimmen nicht überein.', 'error');
        return;
    }

    if (newPassword.length < 8) {
        showMessage('Das neue Passwort muss mindestens 8 Zeichen lang sein.', 'error');
        return;
    }

    const payload = { currentPassword, newPassword };

    try {
        const fd = toFormData(payload, 'data');
        const result = await apiPostFormData('/accountEinstellungen/changePassword', fd);

        if (result.success) {
            closeModal('changePasswordModal');
            showMessage('Passwort wurde geändert.', 'success');
        } else {
            showMessage(result.error || 'Fehler beim Ändern des Passworts.', 'error');
        }
    } catch (e) {
        console.error('Failed to change password:', e);
        showMessage('Fehler beim Ändern des Passworts.', 'error');
    }
}

// ==============================
// WIRE SAVE BUTTONS
// ==============================
function wireSaveButtons() {
    document.getElementById('btnSavePersonal')
        ?.addEventListener('click', savePersonalData);
    document.getElementById('btnSaveUsername')
        ?.addEventListener('click', saveUsername);
    document.getElementById('btnSavePassword')
        ?.addEventListener('click', savePassword);
}

// ==============================
// MODALS
// ==============================
function wireModalClose() {
    document.querySelectorAll('[data-close]').forEach(el =>
        el.addEventListener('click', () => closeModal(el.dataset.close))
    );

    window.addEventListener('click', e => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
}

function clearModalInputs(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.querySelectorAll('input').forEach(input => input.value = '');
}

// ==============================
// HELPERS
// ==============================
function applyDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}

function showMessage(text, type = 'info') {
    const msgEl = document.getElementById('accountMessage');
    if (!msgEl) return;

    msgEl.className = `message message-${type}`;
    msgEl.textContent = text;
    msgEl.style.display = 'block';

    // Auto-hide after 5 seconds
    setTimeout(() => {
        msgEl.style.display = 'none';
    }, 5000);
}
