<?= $this->extend('Layouts/auth-layout') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('auth-form') ?>
    <div class="auth-card">
        <?php if(session("error") != null): ?>
            <div class="auth-error"><?= esc(session('error')) ?></div>
        <?php endif; ?>

        <?= form_open('/login') ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-input">
            </div>

            <button type="submit" class="btn-submit">Login</button>

            <p class="auth-switch">
                Don't have an account? <a href="<?= base_url('/register') ?>" class="auth-switch-link">Register</a>
            </p>
        <?= form_close() ?>
    </div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <script>
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.querySelector('.btn-submit');

        if (loginForm && submitBtn) {
            loginForm.addEventListener('submit', (event) => {
                submitBtn.disabled = true;
                submitBtn.textContent = "Logging in..."
            });
        }
    </script>
<?= $this->endSection() ?>