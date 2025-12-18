<?php
global $pages;
?>
<header>
    <div class="top-banner">
        ENVÍO GRATIS EN COMPRAS SUPERIORES A $150 — NUEVAS LLEGADAS CADA SEMANA
    </div>
    <nav>
        <a href="index.php" class="logo"><?php echo SITE_NAME; ?></a>
        
        <ul class="nav-center">
            <?php foreach ($pages as $slug => $page): ?>
                <?php if ($page['nav']): ?>
                <li>
                    <a href="index.php?page=<?php echo $slug; ?>" 
                       class="<?php echo isActivePage($slug); ?>">
                        <?php echo $page['title']; ?>
                    </a>
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        
        <div class="nav-right">
            <div class="nav-icon search-toggle" id="searchToggle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </div>
            <div class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="nav-icon cart-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 6h15l-1.5 9h-12z"></path>
                    <circle cx="9" cy="20" r="1"></circle>
                    <circle cx="18" cy="20" r="1"></circle>
                    <path d="M6 6L5 1H1"></path>
                </svg>
                <span class="cart-badge">3</span>
            </div>
        </div>
        
        <button class="mobile-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>
</header>

<!-- Search Modal -->
<div class="search-modal" id="searchModal">
    <div class="search-modal-overlay"></div>
    <div class="search-modal-content">
        <button class="search-close" id="searchClose">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"></path>
            </svg>
        </button>
        <form class="search-form" action="index.php" method="GET">
            <input type="hidden" name="page" value="buscar">
            <div class="search-input-wrapper">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input type="text" name="q" class="search-input" 
                       placeholder="Buscar productos..." 
                       autocomplete="off" autofocus>
            </div>
            <button type="submit" class="search-submit">Buscar</button>
        </form>
        <div class="search-suggestions">
            <p class="suggestions-title">Búsquedas populares</p>
            <div class="suggestions-tags">
                <a href="index.php?page=buscar&q=vestido">Vestidos</a>
                <a href="index.php?page=buscar&q=blazer">Blazers</a>
                <a href="index.php?page=buscar&q=camisa">Camisas</a>
                <a href="index.php?page=buscar&q=pantalon">Pantalones</a>
            </div>
        </div>
    </div>
</div>