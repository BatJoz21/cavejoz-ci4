<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Edit Profile<?= $this->endSection() ?>

<?= $this->section('main') ?>

<?php if(!empty($user)): ?>
    <div class="edit-profile-page">
        <h1 class="edit-profile-title">Edit Profile</h1>

        <?= form_open_multipart('/profile') ?>
            <input type="hidden" name="_method" value="PATCH">

            <label for="">Profile Picture</label>
            <div class="edit-avatar-wrapper">
                <img src="<?= base_url('/avatar/' . ($user['avatar_url'] ?? 'default')) ?>" alt="" class="edit-avatar-preview" id="avatarPreview">
                <div class="file-input-wrapper">
                    <button type="button" id="avatarTrigger" class="btn-file-choose">Change Photo</button>
                    <span id="avatarFileName" class="file-name">No new file choosen</span>
                </div>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="file-input-hidden">
            </div>

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="form-input" value="<?= esc($user['full_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-input" value="<?= esc($user['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-input" value="<?= esc($user['email'] ?? '') ?>" disabled>
            </div>

            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea name="bio" id="bio" class="form-input form-textarea" maxlength="150"><?= esc($user['bio'] ?? '') ?></textarea>
                <span class="char-counter" id="bioCounter">150 characters remaining</span>
            </div>

            <button type="submit" class="btn-submit">Save Changes</button>
        <?= form_close() ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>