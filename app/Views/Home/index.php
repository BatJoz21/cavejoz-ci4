<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Home<?= $this->endSection() ?>

<?= $this->section('main') ?>

    <div class="feed-page">
        <div class="post-list" id="postList"></div>
    </div>

<?= $this->endSection() ?>