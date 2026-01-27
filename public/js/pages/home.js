document.getElementById('logout-form')?.addEventListener('submit', e => {
    if (!confirm('Möchten Sie sich wirklich ausloggen?')) {
        e.preventDefault();
    }
});
