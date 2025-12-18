<?php
session_start();
require_once 'conexion/conexion.php';

$sql = "SELECT * FROM `organizations` LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$org) {
    $org = [
        'name' => 'CRM Pro',
        'logo_url' => 'assets/images/collab.png',
        'primary_color' => '#10b981',
        'secondary_color' => '#059669'
    ];
}

$usuario = [
    'nombre' => 'Fernando',
    'email' => 'fernando@ejemplo.com',
    'rol' => 'Administrador'
];

$conn = null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $org['logo_url']; ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Dashboard | <?php echo $org['name']; ?></title>
    <style>
        :root {
            --primary: <?php echo $org['primary_color']; ?>;
            --primary-dark: <?php echo $org['secondary_color']; ?>;
            --primary-light: rgba(16, 185, 129, 0.1);
            --primary-hover: rgba(16, 185, 129, 0.05);
            
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --white: #ffffff;
            
            --sidebar-width: 280px;
            --header-height: 72px;
            
            --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-sans);
            background: var(--gray-50);
            color: var(--gray-900);
            -webkit-font-smoothing: antialiased;
        }
        
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle at 1px 1px, rgba(16, 185, 129, 0.08) 1px, transparent 0);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
        }
        
        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }
        
        /* SIDEBAR MEJORADO CON MÁS ELEMENTOS */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--white);
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow: hidden;
        }
        
        /* Decoración superior */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        }
        
        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0 1.75rem;
            border-bottom: 1px solid var(--gray-200);
            position: relative;
        }
        
        .sidebar-logo-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            flex-shrink: 0;
            position: relative;
        }
        
        .sidebar-logo {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }
        
        .sidebar-brand {
            flex: 1;
            min-width: 0;
        }
        
        .sidebar-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            line-height: 1.2;
        }
        
        .sidebar-tagline {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.125rem;
        }
        
        /* Stats rápidos */
        .sidebar-stats {
            padding: 1rem 1.75rem;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .stats-grid-sidebar {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        
        .stat-item-sidebar {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
        }
        
        .stat-value-sidebar {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        
        .stat-label-sidebar {
            font-size: 0.6875rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 1rem;
            overflow-y: auto;
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }
        
        .nav-section {
            margin-bottom: 1.75rem;
        }
        
        .nav-section:last-child {
            margin-bottom: 0;
        }
        
        .nav-section-title {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0 0.75rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-section-title::before {
            content: '';
            width: 12px;
            height: 2px;
            background: var(--gray-300);
            border-radius: 1px;
        }
        
        .nav-items {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.75rem 0.75rem;
            color: var(--gray-700);
            text-decoration: none;
            font-size: 0.9375rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 8px;
            position: relative;
            cursor: pointer;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--primary);
            border-radius: 0 2px 2px 0;
            transition: height 0.2s ease;
        }
        
        .nav-item:hover {
            background: var(--primary-hover);
            color: var(--gray-900);
        }
        
        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .nav-item.active::before {
            height: 20px;
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .nav-label {
            flex: 1;
        }
        
        .nav-badge {
            padding: 0.125rem 0.5rem;
            font-size: 0.6875rem;
            font-weight: 600;
            background: var(--gray-100);
            color: var(--gray-600);
            border-radius: 10px;
            line-height: 1.4;
        }
        
        .nav-item.active .nav-badge {
            background: var(--primary);
            color: var(--white);
        }
        
        .nav-arrow {
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: var(--gray-400);
            transition: transform 0.2s ease;
        }
        
        .nav-item.has-submenu.open .nav-arrow {
            transform: rotate(90deg);
        }
        
        /* Submenú */
        .nav-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            margin-left: 2.25rem;
            margin-top: 0.25rem;
        }
        
        .nav-submenu.open {
            max-height: 500px;
        }
        
        .nav-subitem {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            color: var(--gray-600);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            border-radius: 6px;
            margin-bottom: 0.125rem;
            position: relative;
        }
        
        .nav-subitem::before {
            content: '';
            width: 4px;
            height: 4px;
            background: var(--gray-400);
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .nav-subitem:hover {
            background: var(--primary-hover);
            color: var(--gray-900);
        }
        
        .nav-subitem:hover::before {
            background: var(--primary);
        }
        
        .nav-subitem.active {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .nav-subitem.active::before {
            background: var(--primary);
        }
        
        /* Footer mejorado */
        .sidebar-footer {
            padding: 1rem 1.75rem;
            border-top: 1px solid var(--gray-200);
            background: var(--gray-50);
        }
        
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .sidebar-user:hover {
            border-color: var(--gray-300);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
            flex-shrink: 0;
            position: relative;
        }
        
        .sidebar-avatar::after {
            content: '';
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 10px;
            height: 10px;
            background: #10b981;
            border: 2px solid var(--white);
            border-radius: 50%;
        }
        
        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }
        
        .sidebar-user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-900);
            line-height: 1.2;
        }
        
        .sidebar-user-role {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.125rem;
        }
        
        .sidebar-user-arrow {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: var(--gray-400);
        }
        
        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
        }
        
        /* HEADER */
        .header {
            height: var(--header-height);
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        
        .breadcrumb-item {
            color: var(--gray-600);
            text-decoration: none;
            transition: color 0.15s ease;
        }
        
        .breadcrumb-item:hover {
            color: var(--gray-900);
        }
        
        .breadcrumb-item.active {
            color: var(--gray-900);
            font-weight: 500;
        }
        
        .breadcrumb-separator {
            color: var(--gray-400);
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .header-search {
            position: relative;
        }
        
        .search-input {
            width: 320px;
            padding: 0.625rem 1rem 0.625rem 2.75rem;
            font-size: 0.875rem;
            font-family: var(--font-sans);
            color: var(--gray-900);
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            outline: none;
            transition: all 0.15s ease;
        }
        
        .search-input:focus {
            background: var(--white);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.875rem;
        }
        
        .header-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
        }
        
        .header-btn:hover {
            background: var(--white);
            color: var(--gray-900);
            border-color: var(--gray-300);
        }
        
        .header-btn .badge-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border: 2px solid var(--white);
            border-radius: 50%;
        }
        
        /* CONTENT AREA */
        .content {
            flex: 1;
            padding: 2.5rem;
        }
        
        .page-header {
            margin-bottom: 2.5rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            letter-spacing: -0.03em;
        }
        
        .page-subtitle {
            font-size: 1rem;
            color: var(--gray-600);
        }
        
        /* STATS CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .stat-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.75rem;
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            border-color: var(--gray-300);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .stat-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        
        .stat-info {
            flex: 1;
        }
        
        .stat-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-600);
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 2.25rem;
            font-weight: 600;
            color: var(--gray-900);
            letter-spacing: -0.02em;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            color: var(--primary);
            font-size: 1.25rem;
        }
        
        .stat-footer {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
        }
        
        .stat-change {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 500;
        }
        
        .stat-change.positive {
            color: var(--primary);
        }
        
        .stat-change.negative {
            color: #ef4444;
        }
        
        .stat-text {
            color: var(--gray-500);
        }
        
        /* ACTIVITY SECTION */
        .activity-section {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 2rem;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .btn-link {
            font-size: 0.875rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s ease;
        }
        
        .btn-link:hover {
            color: var(--primary-dark);
        }
        
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .activity-item {
            display: flex;
            gap: 1rem;
            padding: 1.25rem;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        
        .activity-item:hover {
            background: var(--white);
            border-color: var(--gray-300);
        }
        
        .activity-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            color: var(--primary);
            flex-shrink: 0;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--gray-900);
            margin-bottom: 0.375rem;
        }
        
        .activity-meta {
            font-size: 0.8125rem;
            color: var(--gray-600);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 100;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 1.5rem;
            }
            
            .header {
                padding: 0 1rem;
            }
            
            .header-search {
                display: none;
            }
            
            .breadcrumb {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- SIDEBAR CON SUBNIVELES -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-wrapper">
                    <img src="<?php echo $org['logo_url']; ?>" alt="<?php echo $org['name']; ?>" class="sidebar-logo">
                </div>
                <div class="sidebar-brand">
                    <div class="sidebar-title"><?php echo $org['name']; ?></div>
                    <div class="sidebar-tagline">CRM Platform</div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <div class="nav-items">
                        <a href="dashboard.php" class="nav-item active">
                            <i class="nav-icon fas fa-home"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                        
                        <!-- Item con subniveles -->
                        <div class="nav-item has-submenu" onclick="toggleSubmenu(this)">
                            <i class="nav-icon fas fa-users"></i>
                            <span class="nav-label">Clientes</span>
                            <span class="nav-badge">1.2K</span>
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </div>
                        <div class="nav-submenu">
                            <a href="clientes.php" class="nav-subitem">
                                <span>Todos los Clientes</span>
                            </a>
                            <a href="clientes-activos.php" class="nav-subitem">
                                <span>Clientes Activos</span>
                            </a>
                            <a href="prospectos.php" class="nav-subitem">
                                <span>Prospectos</span>
                            </a>
                            <a href="segmentos.php" class="nav-subitem">
                                <span>Segmentos</span>
                            </a>
                        </div>
                        
                        <!-- Item con subniveles -->
                        <div class="nav-item has-submenu" onclick="toggleSubmenu(this)">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <span class="nav-label">Ventas</span>
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </div>
                        <div class="nav-submenu">
                            <a href="ventas-todas.php" class="nav-subitem">
                                <span>Todas las Ventas</span>
                            </a>
                            <a href="cotizaciones.php" class="nav-subitem">
                                <span>Cotizaciones</span>
                            </a>
                            <a href="facturas.php" class="nav-subitem">
                                <span>Facturas</span>
                            </a>
                            <a href="pagos.php" class="nav-subitem">
                                <span>Pagos</span>
                            </a>
                        </div>
                        
                        <a href="productos.php" class="nav-item">
                            <i class="nav-icon fas fa-box"></i>
                            <span class="nav-label">Productos</span>
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Gestión</div>
                    <div class="nav-items">
                        <!-- Item con subniveles -->
                        <div class="nav-item has-submenu" onclick="toggleSubmenu(this)">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <span class="nav-label">Reportes</span>
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </div>
                        <div class="nav-submenu">
                            <a href="reportes-ventas.php" class="nav-subitem">
                                <span>Reporte de Ventas</span>
                            </a>
                            <a href="reportes-clientes.php" class="nav-subitem">
                                <span>Reporte de Clientes</span>
                            </a>
                            <a href="reportes-productos.php" class="nav-subitem">
                                <span>Reporte de Productos</span>
                            </a>
                        </div>
                        
                        <a href="tareas.php" class="nav-item">
                            <i class="nav-icon fas fa-tasks"></i>
                            <span class="nav-label">Tareas</span>
                            <span class="nav-badge">23</span>
                        </a>
                        <a href="calendario.php" class="nav-item">
                            <i class="nav-icon fas fa-calendar"></i>
                            <span class="nav-label">Calendario</span>
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Sistema</div>
                    <div class="nav-items">
                        <a href="configuracion.php" class="nav-item">
                            <i class="nav-icon fas fa-cog"></i>
                            <span class="nav-label">Configuración</span>
                        </a>
                    </div>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-avatar">F</div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?php echo $usuario['nombre']; ?></div>
                        <div class="sidebar-user-role"><?php echo $usuario['rol']; ?></div>
                    </div>
                    <div class="sidebar-user-arrow">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- HEADER -->
            <header class="header">
                <div class="header-left">
                    <nav class="breadcrumb">
                        <a href="#" class="breadcrumb-item">Inicio</a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item active">Dashboard</span>
                    </nav>
                </div>
                
                <div class="header-right">
                    <div class="header-search">
                        <i class="search-icon fas fa-search"></i>
                        <input type="text" class="search-input" placeholder="Buscar...">
                    </div>
                    
                    <button class="header-btn">
                        <i class="fas fa-bell"></i>
                        <span class="badge-dot"></span>
                    </button>
                </div>
            </header>
            
            <!-- CONTENT -->
            <div class="content">
                <div class="page-header">
                    <h2 class="page-title">Bienvenido, <?php echo $usuario['nombre']; ?></h2>
                    <p class="page-subtitle">Resumen de tu negocio hoy</p>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-info">
                                <div class="stat-label">Clientes Totales</div>
                                <div class="stat-value">1,234</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <div class="stat-change positive">
                                <i class="fas fa-arrow-up"></i>
                                <span>12%</span>
                            </div>
                            <span class="stat-text">vs mes anterior</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-info">
                                <div class="stat-label">Ventas del Mes</div>
                                <div class="stat-value">$45.2K</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <div class="stat-change positive">
                                <i class="fas fa-arrow-up"></i>
                                <span>8%</span>
                            </div>
                            <span class="stat-text">vs mes anterior</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-info">
                                <div class="stat-label">Tareas Pendientes</div>
                                <div class="stat-value">23</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <span class="stat-text">8 vencen hoy</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-info">
                                <div class="stat-label">Tasa de Conversión</div>
                                <div class="stat-value">24.5%</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <div class="stat-change negative">
                                <i class="fas fa-arrow-down"></i>
                                <span>2%</span>
                            </div>
                            <span class="stat-text">vs semana anterior</span>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="activity-section">
                    <div class="section-header">
                        <h3 class="section-title">Actividad Reciente</h3>
                        <a href="#" class="btn-link">Ver todo</a>
                    </div>
                    
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Nuevo cliente registrado</div>
                                <div class="activity-meta">Juan Pérez • Hace 5 minutos</div>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Nueva venta completada</div>
                                <div class="activity-meta">$450.00 • Hace 15 minutos</div>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Tarea completada</div>
                                <div class="activity-meta">Seguimiento cliente • Hace 1 hora</div>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Nuevo mensaje recibido</div>
                                <div class="activity-meta">María López • Hace 2 horas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
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
</body>
</html>