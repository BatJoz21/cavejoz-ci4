<?= $this->include('Layouts/head') ?>
<body>
    <?= $this->include('Layouts/header') ?>

    <?= $this->include('Layouts/sidebar') ?>

    <main class="main-content sidebar-collapsed">
        <?php if(!empty(session('message'))): ?>
            <div class="alert-panel alert-message">
                <i class="bi bi-info-lg"></i>
                <span><?= esc(session('message')) ?></span>
                <button type="button" class="alert-close" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php if(!empty(session('error'))): ?>
            <div class="alert-panel alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                <span><?= esc(session('error')) ?></span>
                <button type="button" class="alert-close" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('main') ?>
    </main>

    <?php if(empty($currentPath)): ?>
        <a href="<?= base_url('/posts/create') ?>" class="fab-create-post" aria-label="Create Post">
            <i class="bi bi-plus"></i>
        </a>
    <?php endif; ?>

    <?= $this->include('Layouts/scripts') ?>
</body>
</html>