<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Search<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <div class="search-page">
        <div class="search-page-input-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="pageSearchInput" id="pageSearchInput" placeholder="Search account..." autofocus>
        </div>

        <div class="search-results" id="searchResults"></div>
    </div>
<?= $this->endSection() ?>