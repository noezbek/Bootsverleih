// public/js/pages/home.js
import {apiPostFormData} from "../api.js";

export function init() {
    bindLogout();
}

function bindLogout() {
    const btn = document.querySelector('[data-logout-button]');
    if (!btn) return;

    btn.addEventListener('click', async () => {
        const ok = confirm('Möchten Sie sich wirklich ausloggen?');
        if (!ok) return;

        try {
            const fd = new FormData();
            fd.append('logout', 'logout');
            await apiPostFormData('/logout', fd);
            window.location.replace(window.APP.baseUrl + '/auth');
        } catch (err) {
            alert('Logout fehlgeschlagen');
            console.error(err);
        }
    });
}