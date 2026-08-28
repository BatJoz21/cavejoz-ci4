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
                <img src="<?= $user['avatar_url'] && $user['avatar_url'] !== 'default' ? base_url('/avatar/' . $user['avatar_url']) : base_url('assets/img/default_avatar.png') ?>" alt="" class="edit-avatar-preview" id="avatarPreview">
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

<?= $this->section('script') ?>
    <script>
        // Profile bio text counter
        const bioTextarea = document.getElementById('bio');
        const bioCounter = document.getElementById('bioCounter');
        const BIO_MAX_LENGTH = 150;

        if(bioTextarea && bioCounter) {
            const updateCounter = () => {
                const remaining = BIO_MAX_LENGTH - bioTextarea.value.length;
                if(remaining > 1) {
                    bioCounter.textContent = `${remaining} characters remaining`;
                } else {
                    bioCounter.textContent = `${remaining} character remaining`;
                }
                bioCounter.style.color = remaining <= 10 ? 'var(--cave-danger)' : '#8b8d94';
            };

            updateCounter();

            bioTextarea.addEventListener('input', updateCounter);
        }
    </script>
    <script src="<?= base_url('js/avatar-upload.js') ?>"></script>
    <script>
        // Live preview for edit profile
        if(avatarTrigger && avatarInput && avatarFileName && avatarPreview) {
            avatarTrigger.addEventListener('click', () => {
                avatarInput.click();
            });

            avatarInput.addEventListener('change', () => {
                if(avatarInput.files.length > 0) {
                    const file = avatarInput.files[0];
                    avatarFileName.textContent = file.name;

                    const reader = new FileReader();
                    reader.onload = (event) => {
                        avatarPreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            avatarFileName.textContent = 'No new file chosen';
        }
    </script>
<?= $this->endSection() ?>