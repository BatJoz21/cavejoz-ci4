<?= $this->include('Layouts/head') ?>
<body>
    <main>
        <div class="auth-page">
            <div class="auth-visual">
                <div class="auth-visual-content">
                    <i class="bi bi-compass auth-visual-icon"></i>
                    <h1 class="auth-visual-title">CaveJoz</h1>
                    <p class="auth-visual-tagline">Explore the depths. Connect in the dark.</p>
                </div>
            </div>

            <div class="auth-form-side">
                <?= $this->renderSection('auth-form') ?>
            </div>
        </div>
    </main>

    <?= $this->include('Layouts/scripts') ?>

    <?= $this->renderSection('script') ?>
</body>
</html>