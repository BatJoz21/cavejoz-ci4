<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>New Post<?= $this->endSection() ?>

<?= $this->section('main') ?>

    <div class="create-post-page">
        <h1 class="create-post-title">Create Post</h1>

        <?= form_open_multipart('/posts') ?>

            <div class="form-group">
                <label>Photo</label>
                <div class="post-image-upload" id="postImageUpload">
                    <img src="" alt="" class="post-image-preview" id="postImagePreview" style="display: none;">
                    <div class="post-image-placeholder" id="postImagePlaceholder">
                        <i class="bi bi-card-image"></i>
                        <p>Click to select photo</p>
                    </div>
                </div>
                <input type="file" name="content" id="postImage" accept="image/*" class="file-input-hidden" required>
            </div>

            <div class="form-group">
                <label>Who can see this?</label>
                <div class="visibility-options">
                    <label class="visibility-option">
                        <input type="radio" name="visibility" id="visibility" value="public" checked>
                        <span class="visibility-option-content">
                            <i class="bi bi-globe2"></i>
                            <span class="visibility-option-label">Public</span>
                            <span class="visibility-option-desc">Anyone on CaveJoz</span>
                        </span>
                    </label>

                    <label class="visibility-option">
                        <input type="radio" name="visibility" id="visibility" value="friends">
                        <span class="visibility-option-content">
                            <i class="bi bi-people-fill"></i>
                            <span class="visibility-option-label">Friends</span>
                            <span class="visibility-option-desc">Only you friends</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="caption">Caption</label>
                <textarea name="caption" id="caption" class="form-input form-textarea" maxlength="500"><?= old('caption') ?></textarea>
                <span class="char-counter" id="captionCounter">500 characters remaining</span>
            </div>

            <button type="submit" class="btn-submit">Share post</button>
        <?= form_close() ?>
    </div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <script src="<?= base_url('js/content-image-upload.js') ?>"></script>
    <script src="<?= base_url('js/caption-text-counter.js') ?>"></script>
<?= $this->endSection() ?>