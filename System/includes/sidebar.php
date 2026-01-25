<?php

require_once 'sidebar-counts.php';

// Obtener conteos dinámicos
$sidebarCounts = [
    'inbox_unread' => 0,
    'tasks_pending' => 0,
    'tickets_open' => 0
];

// Obtener el ID de organización
$orgId = isset($org['id']) ? (int)$org['id'] : 1;
$currentUserId = isset($usuario['id']) ? (int)$usuario['id'] : null;

// Llamar función de conteos (pasando $conn como parámetro)
if (isset($conn) && $conn instanceof PDO) {
    $sidebarCounts = getSidebarCounts($conn, $orgId, $currentUserId);
}

?>
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo-wrapper">
            <img src="<?php echo htmlspecialchars($org['logo_url'] ?? 'assets/images/logo.png'); ?>" 
                 alt="<?php echo htmlspecialchars($org['name'] ?? 'CRM'); ?>" 
                 class="sidebar-logo">
        </div>
        <div class="sidebar-brand">
            <div class="sidebar-title"><?php echo htmlspecialchars($org['name'] ?? 'CRM Pro'); ?></div>
            <div class="sidebar-tagline">CRM Platform</div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <!-- ═══════════════════════════════════ -->
        <!-- PRINCIPAL -->
        <!-- ═══════════════════════════════════ -->
        <div class="nav-section">
            <div class="nav-section-title">Principal</div>
            <div class="nav-items">
                <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
                
                <a href="inbox.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'inbox.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-inbox"></i>
                    <span class="nav-label">Inbox</span>
                    <?php if ($sidebarCounts['inbox_unread'] > 0): ?>
                        <span class="nav-badge"><?php echo $sidebarCounts['inbox_unread'] > 99 ? '99+' : $sidebarCounts['inbox_unread']; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        
        <!-- ═══════════════════════════════════ -->
        <!-- CRM -->
        <!-- ═══════════════════════════════════ -->
        <div class="nav-section">
            <div class="nav-section-title">CRM</div>
            <div class="nav-items">
                <!-- Contactos & Empresas -->
                <div class="nav-item has-submenu" onclick="toggleSubmenu(this)">
                    <i class="nav-icon fas fa-address-book"></i>
                    <span class="nav-label">Contactos</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </div>
                <div class="nav-submenu">
                    <a href="contacts.php" class="nav-subitem"><span>Personas</span></a>
                    <a href="businnes.php" class="nav-subitem"><span>Empresas</span></a>
                </div>
                
                <!-- Leads & Oportunidades (Pipeline) -->
                <div class="nav-item has-submenu" onclick="toggleSubmenu(this)">
                    <i class="nav-icon fas fa-funnel-dollar"></i>
                    <span class="nav-label">Pipeline</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </div>
                <div class="nav-submenu">
                    <a href="leads.php" class="nav-subitem"><span>Leads</span></a>
                    <a href="oportunidades.php" class="nav-subitem"><span>Oportunidades</span></a>
                </div>
                
                <!-- Actividades -->
                <div class="nav-item has-submenu" onclick="toggleSubmenu(this)">
                    <i class="nav-icon fas fa-tasks"></i>
                    <span class="nav-label">Actividades</span>
                    <?php if ($sidebarCounts['tasks_pending'] > 0): ?>
                        <span class="nav-badge"><?php echo $sidebarCounts['tasks_pending'] > 99 ? '99+' : $sidebarCounts['tasks_pending']; ?></span>
                    <?php endif; ?>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </div>
                <div class="nav-submenu">
                    <a href="tareas.php" class="nav-subitem"><span>Tareas</span></a>
                    <a href="calendario.php" class="nav-subitem"><span>Calendario</span></a>
                    <a href="llamadas.php" class="nav-subitem"><span>Llamadas</span></a>
                    <a href="reuniones.php" class="nav-subitem"><span>Reuniones</span></a>
                </div>
            </div>
        </div>
        
        <!-- ═══════════════════════════════════ -->
        <!-- VENTAS -->
        <!-- ═══════════════════════════════════ -->
        <div class="nav-section">
            <div class="nav-section-title">Ventas</div>
            <div class="nav-items">
                <a href="productos.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'productos.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-cube"></i>
                    <span class="nav-label">Productos</span>
                </a>
                
                <div class="nav-item has-submenu" onclick="toggleSubmenu(this)">
                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                    <span class="nav-label">Comercial</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </div>
                <div class="nav-submenu">
                    <a href="cotizaciones.php" class="nav-subitem"><span>Cotizaciones</span></a>
                    <a href="ordenes.php" class="nav-subitem"><span>Órdenes</span></a>
                    <a href="facturas.php" class="nav-subitem"><span>Facturas</span></a>
                </div>
            </div>
        </div>
        
        <!-- ═══════════════════════════════════ -->
        <!-- SOPORTE -->
        <!-- ═══════════════════════════════════ -->
        <div class="nav-section">
            <div class="nav-section-title">Soporte</div>
            <div class="nav-items">
                <a href="tickets.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'tickets.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-headset"></i>
                    <span class="nav-label">Tickets</span>
                    <?php if ($sidebarCounts['tickets_open'] > 0): ?>
                        <span class="nav-badge warning"><?php echo $sidebarCounts['tickets_open'] > 99 ? '99+' : $sidebarCounts['tickets_open']; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        
        <!-- ═══════════════════════════════════ -->
        <!-- MARKETING -->
        <!-- ═══════════════════════════════════ -->
        <div class="nav-section">
            <div class="nav-section-title">Marketing</div>
            <div class="nav-items">
                <div class="nav-item has-submenu" onclick="toggleSubmenu(this)">
                    <i class="nav-icon fas fa-bullhorn"></i>
                    <span class="nav-label">Campañas</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </div>
                <div class="nav-submenu">
                    <a href="campanas.php" class="nav-subitem"><span>Todas</span></a>
                    <a href="segmentos.php" class="nav-subitem"><span>Segmentos</span></a>
                    <a href="formularios.php" class="nav-subitem"><span>Formularios</span></a>
                    <a href="landing-pages.php" class="nav-subitem"><span>Landing Pages</span></a>
                </div>
            </div>
        </div>
        
        <!-- ═══════════════════════════════════ -->
        <!-- SISTEMA -->
        <!-- ═══════════════════════════════════ -->
        <div class="nav-section">
            <div class="nav-section-title">Sistema</div>
            <div class="nav-items">
                <a href="documentos.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'documentos.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-folder-open"></i>
                    <span class="nav-label">Documentos</span>
                </a>
                
                <a href="reportes.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'reportes.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <span class="nav-label">Reportes</span>
                </a>
                
                <a href="configuracion.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'configuracion.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-cog"></i>
                    <span class="nav-label">Configuración</span>
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Pagina Web</div>
            <div class="nav-items">
                <a href="design-system.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'design-system.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-folder-open"></i>
                    <span class="nav-label">Diseño</span>
                </a>

                <a href="home-system.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'home-system.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-folder-open"></i>
                    <span class="nav-label">Inicio</span>
                </a>
                
                <a href="newcollection-system.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'newcollection-system.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <span class="nav-label">Nueva Colección</span>
                </a>
                
                <a href="men-system.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'men-system.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-cog"></i>
                    <span class="nav-label">Hombres</span>
                </a>

                <a href="women-system.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'women-system.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-cog"></i>
                    <span class="nav-label">Mujeres</span>
                </a>

                <a href="sales-system.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'sales-system.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-cog"></i>
                    <span class="nav-label">Sales</span>
                </a>
            </div>
        </div>
    </nav>
    
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar"><?php echo strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1)); ?></div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name"><?php echo htmlspecialchars($usuario['nombre'] ?? 'Usuario'); ?></div>
                <div class="sidebar-user-role"><?php echo htmlspecialchars($usuario['rol'] ?? 'Usuario'); ?></div>
            </div>
            <div class="sidebar-user-arrow">
                <i class="fas fa-ellipsis-h"></i>
            </div>
        </div>
    </div>
</aside>

<script>
    // Toggle submenu
    function toggleSubmenu(element) {
        const submenu = element.nextElementSibling;
        const isOpen = element.classList.contains('open');
        
        if (isOpen) {
            element.classList.remove('open');
            submenu.classList.remove('open');
        } else {
            element.classList.add('open');
            submenu.classList.add('open');
        }
    }
</script>