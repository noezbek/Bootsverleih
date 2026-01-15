// public/js/app.js

function initDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}

async function initPage() {
    const page = document.body?.dataset?.page;
    if (!page) return;

    // home -> /pages/home.js
    // kundenverwaltung -> /pages/kundenverwaltung/kundenverwaltung.js
    const path =
        page === 'home'
            ? `./pages/home.js`
            : `./pages/${page}/${page}.js`;

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
    await initPage();
});
