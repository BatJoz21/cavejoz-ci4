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

            <?php if(!empty($currentPage) && !empty($totalPage)): ?>
                <div class="pagination">
                    <a href="<?= base_url('/search?pageSearchInput=' . $search . '&page=1') ?>" class="pagination-btn <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>

                    <?php for($i = 1; $i <= $totalPage; $i++): ?>
                        <?php if($i == $currentPage - 1 || $i == $currentPage || $i == $currentPage + 1): ?>
                            <a href="<?= base_url('/search?pageSearchInput=' . $search . '&page=' . $i) ?>" class="pagination-btn <?= ($currentPage == $i) ? 'disabled' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <a href="<?= base_url('/search?pageSearchInput=' . $search . '&page=' . $totalPage) ?>" class="pagination-btn <?= ($currentPage == $totalPage) ? 'disabled' : '' ?>">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-search"></i>
                <p class="empty-title">Search people on CaveJoz...</p>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>