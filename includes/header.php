<?php
// NOTA: La lógica de sesión (auth-logic.php) debe cargarse en index.php, no aquí.
global $pages;
require_once __DIR__ . '/../System/apariencia/design-config.php';
?>
<header>
    <?php
    // Verificamos si la configuración dice que sea visible (1)
    // Usamos !empty para evitar errores si el campo está vacío
    if (!empty($datos_actuales['banner_visible']) && $datos_actuales['banner_visible'] == 1):
    ?>

        <div class="top-banner">
            <?php echo htmlspecialchars($TOP_BANNER_TEXT); ?>
        </div>

    <?php endif; ?>
    <nav>
        <a href="index.php" class="logo"><?php echo htmlspecialchars($SITE_NAME); ?></a>
        
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

            <div class="nav-icon user-dropdown-container" id="userTrigger" onclick="toggleUserModal()" style="cursor: pointer;">
                
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>

                <div id="userModal" class="user-modal" onclick="event.stopPropagation()">
                    
                    <?php if (!empty($auth_message)): ?>
                        <div style="padding: 10px; margin-bottom: 10px; background: <?php echo $auth_type == 'error' ? '#ffebee' : '#e8f5e9'; ?>; color: <?php echo $auth_type == 'error' ? '#c62828' : '#2e7d32'; ?>; font-size: 0.9em; border-radius: 4px;">
                            <?php echo $auth_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-content logged-in" style="text-align: center;">
                            <div style="width: 50px; height: 50px; background: var(--color-tertiary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin: 0 auto 10px;">
                                <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                            </div>
                            <p class="welcome-text">Hola, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></p>
                            <a href="?logout=true" class="btn-primary btn-full">Cerrar Sesión</a>
                        </div>
                    <?php else: ?>
                        <div class="user-content">
                            <div id="loginForm">
                                <h4>Iniciar Sesión</h4>
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="login">
                                    <div class="input-group">
                                        <input type="email" name="email" placeholder="Correo" required>
                                    </div>
                                    <div class="input-group">
                                        <input type="password" name="password" placeholder="Contraseña" required>
                                    </div>
                                    <button type="submit" class="btn-primary btn-full">Ingresar</button>
                                </form>
                                <div class="modal-footer">
                                    <span>¿Nuevo?</span>
                                    <a href="#" onclick="switchAuthForm('register'); return false;">Crear cuenta</a>
                                </div>
                            </div>
                            <div id="registerForm" style="display: none;">
                                <h4>Crear Cuenta</h4>
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="register">
                                    <div class="input-group"><input type="text" name="first_name" placeholder="Nombre" required></div>
                                    <div class="input-group"><input type="text" name="last_name" placeholder="Apellido" required></div>
                                    <div class="input-group"><input type="email" name="email" placeholder="Correo" required></div>
                                    <div class="input-group"><input type="password" name="password" placeholder="Contraseña" required></div>
                                    <button type="submit" class="btn-secondary btn-full">Registrarse</button>
                                </form>
                                <div class="modal-footer">
                                    <a href="#" onclick="switchAuthForm('login'); return false;">Volver al login</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
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

<script>
    // Alternar visibilidad del modal principal
    function toggleUserModal() {
        const modal = document.getElementById('userModal');
        modal.classList.toggle('active');
    }

    //Automatización de tamaño cuando desaparezca el banner superior
    function ajustarAlturaHeader() {
        const header = document.querySelector('header');
        if (header) {
            // 1. Mide cuánto mide realmente el header (con o sin banner)
            const alturaReal = header.offsetHeight;
            
            // 2. Actualiza la variable CSS globalmente
            document.documentElement.style.setProperty('--header-height', alturaReal + 'px');
        }
    }

    // Ejecutamos en varios momentos para asegurar que no falle:
    // 1. Cuando el DOM esté listo (rápido)
    document.addEventListener('DOMContentLoaded', ajustarAlturaHeader);
    // 2. Cuando todo (imágenes, fuentes) haya cargado (seguro)
    window.addEventListener('load', ajustarAlturaHeader);
    // 3. Si el usuario cambia el tamaño de la ventana
    window.addEventListener('resize', ajustarAlturaHeader);

    // Alternar entre Login y Registro dentro del modal
    function switchAuthForm(formType) {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        if (formType === 'register') {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        } else {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        }
    }

    // Cerrar modal si se hace clic fuera de él
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('userModal');
        // Ahora sí usamos el ID correcto
        const btn = document.getElementById('userTrigger');
        
        // Si el clic no fue en el modal ni en el botón, cerramos
        if (!modal.contains(e.target) && !btn.contains(e.target)) {
            modal.classList.remove('active');
        }
    });
</script>