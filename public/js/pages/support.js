export function init() {
    applyDarkMode();
    wireForm();
}

function applyDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}

function wireForm() {
    const form = document.getElementById('supportForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        alert(
            'Vielen Dank für Ihre Anfrage! Wir werden uns in Kürze bei Ihnen melden.\n\n' +
            'Ticket-ID: #' + Math.floor(Math.random() * 10000)
        );

        form.reset();
    });
}
