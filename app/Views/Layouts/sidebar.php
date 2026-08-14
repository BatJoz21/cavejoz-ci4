<aside class="cavejoz-sidebar collapsed">
    <nav class="sidebar-nav">
        <a href="<?= base_url('/') ?>" class="sidebar-link"><i class="bi bi-house"></i><span>Home</span></a>
        <a href="<?= base_url('/profile') ?>" class="sidebar-link"><i class="bi bi-person"></i><span>Profile</span></a>
        <a href="<?= base_url('/friends') ?>" class="sidebar-link"><i class="bi bi-people"></i><span>Friends</span></a>
        <a href="<?= base_url('/chat') ?>" class="sidebar-link"><i class="bi bi-chat"></i><span>Messages</span></a>
    </nav>

    <?= form_open('/logout') ?>
        <div class="sidebar-footer">
            <button id="logoutButton" type="submit" class="sidebar-link"><i class="bi bi-box-arrow-left"></i><span>Logout</span></button>
        </div>
    <?= form_close() ?>
</aside>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>