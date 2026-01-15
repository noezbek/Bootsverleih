// public/js/pages/home.js
export function init() {
    const logoutLink = document.querySelector('.logout-item');
    if (!logoutLink) return;

    logoutLink.addEventListener('click', (e) => {
        if (!confirm('Möchten Sie sich wirklich ausloggen?')) {
            e.preventDefault();
        }
    });
}