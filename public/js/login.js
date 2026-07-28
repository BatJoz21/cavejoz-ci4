const loginForm = document.getElementById('loginForm');
const submitBtn = document.querySelector('.btn-submit');

if (loginForm && submitBtn) {
    loginForm.addEventListener('submit', (event) => {
        submitBtn.disabled = true;
        submitBtn.textContent = "Logging in..."
    });
}