const themeButton = document.querySelector('[data-theme-toggle]');
const themeLabel = document.querySelector('[data-theme-label]');
const passwordInput = document.querySelector('#password');
const passwordButton = document.querySelector('[data-password-toggle]');
const loginForm = document.querySelector('.login-form');

const syncThemeControl = () => {
    const light = document.documentElement.dataset.theme === 'light';
    if (themeButton) {
        themeButton.setAttribute('aria-pressed', String(light));
        themeButton.setAttribute('aria-label', light ? 'Beralih ke mode gelap' : 'Beralih ke mode terang');
    }
    if (themeLabel) themeLabel.textContent = light ? 'Mode gelap' : 'Mode terang';
};

syncThemeControl();

themeButton?.addEventListener('click', () => {
    const nextTheme = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
    document.documentElement.dataset.theme = nextTheme;
    try { localStorage.setItem('arsip-theme', nextTheme); } catch (_) {}
    syncThemeControl();
});

passwordButton?.addEventListener('click', () => {
    if (!passwordInput) return;
    const showPassword = passwordInput.type === 'password';
    passwordInput.type = showPassword ? 'text' : 'password';
    passwordButton.setAttribute('aria-pressed', String(showPassword));
    passwordButton.setAttribute('aria-label', showPassword ? 'Sembunyikan password' : 'Tampilkan password');
});

loginForm?.addEventListener('submit', () => {
    const submitButton = loginForm.querySelector('.btn-login');
    if (!submitButton) return;
    submitButton.disabled = true;
    submitButton.classList.add('is-loading');
    submitButton.setAttribute('aria-busy', 'true');
    submitButton.querySelector('.btn-label').textContent = 'Memproses...';
});
