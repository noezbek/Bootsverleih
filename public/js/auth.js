export function init() {
    setupSwitching();
    setupPasswordToggle();
    setupRegisterValidation();
}

function setupSwitching() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    document.querySelectorAll('[data-switch]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.dataset.switch === 'login') {
                loginForm.classList.add('active');
                registerForm.classList.remove('active');
            } else {
                registerForm.classList.add('active');
                loginForm.classList.remove('active');
            }
        });
    });
}

function setupPasswordToggle() {
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
}

function setupRegisterValidation() {
    const form = document.querySelector('#register-form form');
    if (!form) return;

    const errorBox = document.getElementById('register-error');

    form.addEventListener('submit', e => {
        errorBox.textContent = '';

        const pwd = form.querySelector('#password').value;
        const pwdRepeat = form.querySelector('#password_repeat').value;

        if (pwd !== pwdRepeat) {
            e.preventDefault();
            errorBox.textContent = 'Passwörter stimmen nicht überein.';
            return;
        }

        if (pwd.length < 6) {
            e.preventDefault();
            errorBox.textContent = 'Passwort muss mindestens 6 Zeichen haben.';
        }
    });
}

init();
