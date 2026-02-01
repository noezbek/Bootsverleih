// public/js/app.js

import {apiGetMany} from "./services/api.js";

window.APP = window.APP || {};

function initDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}

async function bootstrapApp() {
    // nur einmal
    if (window.APP._bootstrapped) return;
    window.APP._bootstrapped = true;

    try {

        const {user, enums} = await apiGetMany({
            enums: '/enums/load',
            user: '/userdata/load',
        })

        window.APP.user = user;
        window.APP.enums = enums;

        console.log(window.APP);
    } catch (e) {
        // 401 wird bereits in apiFetch gehandhabt
        window.APP.user = null;
        window.APP.enums = {};
    }
}

async function initPage() {
    const page = document.body?.dataset?.page;
    if (!page) return;

    // home -> /pages/home.js
    // kundenverwaltung -> /pages/kundenverwaltung.js
    const path = `./pages/${page}.js`;

    try {
        const mod = await import(path);
        if (typeof mod.init === 'function') {
            await mod.init();
        }
    } catch (e) {
        console.error(`[app.js] Konnte Page-Modul nicht laden: ${path}`, e);
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    initDarkMode();
    await bootstrapApp();
    await initPage();
});
