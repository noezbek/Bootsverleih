export function init() {
    wireDarkMode();
    wireEmailNotifications();
    wireEditProfile();
}

// --------------------
// Dark Mode
// --------------------
function wireDarkMode() {
    const toggle = document.getElementById('darkModeToggle');
    if (!toggle) return;

    const isDark = localStorage.getItem('darkMode') === 'true';

    if (isDark) {
        document.body.classList.add('dark-mode');
        toggle.checked = true;
    }

    toggle.addEventListener('change', () => {
        document.body.classList.toggle('dark-mode', toggle.checked);
        localStorage.setItem('darkMode', toggle.checked);
    });
}

// --------------------
// Email Notifications
// --------------------
function wireEmailNotifications() {
    const toggle = document.getElementById('emailNotifications');
    if (!toggle) return;

    toggle.checked = localStorage.getItem('emailNotifications') === 'true';

    toggle.addEventListener('change', () => {
        localStorage.setItem('emailNotifications', toggle.checked);
    });
}

// --------------------
// Profile Edit (Demo)
// --------------------
function wireEditProfile() {
    document
        .getElementById('editProfileBtn')
        ?.addEventListener('click', () => {
            alert('Funktion in Entwicklung');
        });
}
