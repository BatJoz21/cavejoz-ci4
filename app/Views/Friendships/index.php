<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Friends<?= $this->endSection() ?>

<?= $this->section('main') ?>

    <?php
        if(empty($status)) {
            $status = 'accepted';
        }

        $message1 = 'No friends yet';
        $message2 = "When you add friends, they'll show up here.";
        if($status === 'pending') {
            $message1 = 'No friend requests yet';
            $message2 = "When you have friend requests, they'll show up here.";
        }
    ?>

    <div class="friends-page">
        <div class="friends-tabs">
            <a href="<?= base_url('/friends?status=accepted') ?>" class="friends-tab-friend <?= ($status === 'accepted') ? 'active' : '' ?>">Friends</a>
            <a href="<?= base_url('/friends?status=pending') ?>" class="friends-tab-pending <?= ($status === 'pending') ? 'active' : '' ?>">Pending</a>
            <a href="<?= base_url('/friends?status=blocked') ?>" class="friends-tab-blocked <?= ($status === 'blocked') ? 'active' : '' ?>">Blocked</a>
        </div>

        <?php if(!empty($users)): ?>
            <div class="friends-list">
                <?php foreach($users as $user): ?>
                    <div class="friend-item">
                        <img src="<?= base_url('/avatar/' . ($user['avatar_url'] ?? 'default')) ?>" alt="" class="friend-avatar">
                        <div class="friend-info">
                            <span class="friend-username"><?= esc($user['username']) ?></span>
                            <span class="friend-fullname"><?= esc($user['full_name']) ?></span>
                        </div>

                        <div class="friend-action">
                            <?php if($status !== 'blocked'): ?>
                                <a href="<?= base_url('/profile/' . $user['username']) ?>" class="btn-friend-view">Profile</a>
                                <?php if($status === 'pending'): ?>
                                    <?= form_open('/friends') ?>
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="hidden" name="friendship_id" value="<?= esc($user['friendship_id']) ?>">
                                        <button type="submit" class="btn-friend-accept">Accept</button>
                                    <?= form_close() ?>
                                    <?= form_open('/friends') ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="friendship_id" value="<?= esc($user['friendship_id']) ?>">
                                        <button type="submit" class="btn-friend-decline">Decline</button>
                                    <?= form_close() ?>
                                <?php elseif($status === 'accepted'): ?>
                                    <?= form_open('/friends') ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="friendship_id" value="<?= esc($user['friendship_id']) ?>">
                                        <button type="submit" class="btn-friend-decline">Remove</button>
                                    <?= form_close() ?>
                                <?php endif; ?>
                                <?= form_open('/block') ?>
                                    <input type="hidden" name="addressee_id" value="<?= esc($user['friend_u_id']) ?>">
                                    <button type="submit" class="btn-friend-decline">Block</button>
                                <?= form_close() ?>
                            <?php else: ?>
                                <?= form_open('/friends') ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="friendship_id" value="<?= esc($user['friendship_id']) ?>">
                                    <button type="submit" class="btn-friend-decline">Remove</button>
                                <?= form_close() ?>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="friend-empty-state">
                <i class="bi bi-people"></i>
                <p class="friend-empty-title"><?= $message1 ?></p>
                <p class="friend-empty-subtitle"><?= $message2 ?></p>
                <?php if($status === 'accepted'): ?>
                    <a href="<?= base_url('/search') ?>" class="btn-empty-cta">Search for people</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>