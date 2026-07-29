<?= $this->extend('Layouts/auth-layout') ?>

<?= $this->section('title') ?>Register<?= $this->endSection() ?>

<?= $this->section('auth-form') ?>
    <div class="auth-card">
        <?php if(session("error") != null): ?>
            <div class="auth-error"><?= esc(session('error')) ?></div>
        <?php endif; ?>
        <?php if(session("errors") != null): ?>
            <div class="auth-error">
                <ul>
                    <?php foreach(session("errors") as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?= form_open_multipart('/register') ?>
            <div class="form-group">
                <label for="full_name">Full name</label>
                <input type="text" name="full_name" id="full_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="avatar" class="form-label">Avatar</label>
                <div class="file-input-wrapper">
                    <button type="button" id="avatarTrigger" class="btn-file-choose">Choose File</button>
                    <span id="avatarFileName" class="file-name">No file choosen</span>
                </div>
                <input type="file" name="avatar" id="avatar" class="file-input-hidden">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-input">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-input">
            </div>

            <button type="submit" class="btn-submit">Create Account</button>
        <?= form_close() ?>

        <p class="auth-switch">
            Have an account? <a href="<?= base_url('/login') ?>" class="auth-switch-link">Login</a>
        </p>
    </div>
<?= $this->endSection() ?>