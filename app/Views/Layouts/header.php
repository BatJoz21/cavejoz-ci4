<header class="cavejoz-header">
    <button id="sidebarToggle" class="header-toggle" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
    </button>
    
    <a href="<?= base_url('/') ?>" class="header-brand">
        <i class="bi bi-compass"></i>
        <span>CaveJoz</span>
    </a>
    <div class="header-search">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Search...">
        <button type="submit" id="searchSubmit" aria-label="Search"><i class="bi bi-arrow-right"></i>
        </button>
    </div>
    <div class="header-notification">
        <i class="bi bi-bell"></i>
        <span class="notif-dot"></span>
    </div>
</header>