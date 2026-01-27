const loginBox = document.getElementById('loginBox');
const registerBox = document.getElementById('registerBox');

const btnLogin = document.getElementById('showLogin');
const btnRegister = document.getElementById('showRegister');

function showLogin() {
    loginBox.classList.remove('hidden');
    registerBox.classList.add('hidden');

    btnLogin.classList.add('active');
    btnRegister.classList.remove('active');
}

function showRegister() {
    registerBox.classList.remove('hidden');
    loginBox.classList.add('hidden');

    btnRegister.classList.add('active');
    btnLogin.classList.remove('active');
}

btnLogin.onclick = showLogin;
btnRegister.onclick = showRegister;

document.querySelectorAll('[data-switch]').forEach(el => {
    el.addEventListener('click', () => {
        el.dataset.switch === 'login' ? showLogin() : showRegister();
    });
});
