export function init() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    function showLogin() {
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
    }

    function showRegister() {
        registerForm.classList.add('active');
        loginForm.classList.remove('active');
    }

    document.querySelectorAll('[data-switch]').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.dataset.switch === 'login'
                ? showLogin()
                : showRegister();
        });
    });
}

init();
