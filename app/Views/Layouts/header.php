<header class="cavejoz-header">
    <button id="sidebarToggle" class="header-toggle" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
    </button>
    
    <a href="<?= base_url('/') ?>" class="header-brand">
        <i class="bi bi-compass"></i>
        <span>CaveJoz</span>
    </a>
    <a href="<?= base_url('search') ?>" class="header-search-link" aria-label="Search">
        <i class="bi bi-search"></i>
    </a>
    <?= view_cell('App\Cells\NotificationBellCell', null, 60) ?>
</header>