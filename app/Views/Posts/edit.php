<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Edit Post<?= $this->endSection() ?>

<?= $this->section('main') ?>

<?php if(!empty($post)): ?>
    <div class="create-post-page">
        <h1 class="create-post-title">Edit Post</h1>

        <?= form_open_multipart('/posts/' . $post['id']) ?>
            <input type="hidden" name="_method" value="PATCH">

            <div class="form-group">
                <label>Photo</label>
                <?php $hasContent = !empty($post['content_url']) ?>
                <div class="post-image-upload" id="postImageUpload">
                    <img src="<?= $hasContent ? base_url('/content/image/' . $post['content_url']) : '' ?>" alt="" class="post-image-preview" id="postImagePreview" style="display: <?= $hasContent ? 'block' : 'none' ?>;">
                    <div class="post-image-placeholder" id="postImagePlaceholder" style="display: <?= $hasContent ? 'none' : 'block' ?>;">
                        <i class="bi bi-card-image"></i>
                        <p>Click to select photo</p>
                    </div>
                </div>
                <input type="file" name="content" id="postImage" accept="image/*" class="file-input-hidden">
            </div>

            <div class="form-group">
                <label>Who can see this?</label>
                <div class="visibility-options">
                    <label class="visibility-option">
                        <input type="radio" name="visibility" id="visibility" value="public" <?= ($post['visibility'] == 'public') ? 'checked' : '' ?>>
                        <span class="visibility-option-content">
                            <i class="bi bi-globe2"></i>
                            <span class="visibility-option-label">Public</span>
                            <span class="visibility-option-desc">Anyone on CaveJoz</span>
                        </span>
                    </label>

                    <label class="visibility-option">
                        <input type="radio" name="visibility" id="visibility" value="friends" <?= ($post['visibility'] == 'friends') ? 'checked' : '' ?>>
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
                <textarea name="caption" id="caption" class="form-input form-textarea" maxlength="500"><?= esc($post['caption']) ?></textarea>
                <span class="char-counter" id="captionCounter">500 characters remaining</span>
            </div>

            <button type="submit" class="btn-submit">Update post</button>
        <?= form_close() ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <script src="<?= base_url('js/content-image-upload.js') ?>"></script>
    <script src="<?= base_url('js/caption-text-counter.js') ?>"></script>
<?= $this->endSection() ?>