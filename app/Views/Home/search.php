<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Search<?= $this->endSection() ?>

<?= $this->section('main') ?>

    <div class="search-page">
        <?= form_open('/search', ['method' => 'get']) ?>
            <div class="search-page-input-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" name="pageSearchInput" id="pageSearchInput" placeholder="Search account..." value="<?= esc($search ?? '') ?>" autofocus>
                <button type="submit">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        <?= form_close() ?>

        <?php if(!empty($searchResults)): ?>
            <?php foreach($searchResults as $result): ?>
                <a href="<?= base_url('/profile/' . esc($result['username'])) ?>" class="search-result-item">
                    <?php if(!empty($result['avatar_url'])): ?>
                        <img src="<?= base_url('/avatar/' . $result['avatar_url']) ?>" alt="" class="search-result-avatar">
                    <?php else: ?>
                        <img src="<?= base_url('avatar/default_user.png') ?>" alt="" class="search-result-avatar">
                    <?php endif; ?>
                    <div class="search-result-info">
                        <span class="class-search-username"><?= esc($result['username']) ?></span>
                        <span class="class-search-fullname"><?= esc($result['full_name']) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="search-empty-state">
                <i class="bi bi-search"></i>
                <p>Search people on CaveJoz...</p>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>