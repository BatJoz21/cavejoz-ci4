<?= $this->include('Layouts/head') ?>
<body>
    <?= $this->include('Layouts/header') ?>

    <?= $this->include('Layouts/sidebar') ?>

    <main class="main-content">
        <?= $this->renderSection('main') ?>
    </main>

    <?= $this->include('Layouts/scripts') ?>
</body>
</html>