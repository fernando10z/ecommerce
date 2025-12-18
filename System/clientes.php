<?php
session_start();
require_once 'conexion/conexion.php';

// Obtener datos de la organización
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

// Datos del usuario
$usuario = [
    'nombre' => 'Fernando',
    'email' => 'fernando@ejemplo.com',
    'rol' => 'Administrador'
];

// Obtener clientes (ejemplo)
$sql = "SELECT * FROM leads ORDER BY first_name DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$conn = null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $org['logo_url']; ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <title>Gestión de Clientes | <?php echo $org['name']; ?></title>
    <style>
        :root {
            --primary: <?php echo $org['primary_color']; ?>;
            --primary-dark: <?php echo $org['secondary_color']; ?>;
            --primary-light: rgba(16, 185, 129, 0.08);
            
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
            --white: #ffffff;
            
            --sidebar-width: 240px;
            --header-height: 56px;
            
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
            font-size: 14px;
        }
        
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle at 1px 1px, rgba(16, 185, 129, 0.06) 1px, transparent 0);
            background-size: 24px 24px;
            pointer-events: none;
            z-index: 0;
        }
        
        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }
        
        /* SIDEBAR MINIMALISTA Y COMPACTO */
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
        
        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0 1.25rem;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .sidebar-logo-wrapper {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            border-radius: 6px;
            flex-shrink: 0;
        }
        
        .sidebar-logo {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }
        
        .sidebar-brand {
            flex: 1;
            min-width: 0;
        }
        
        .sidebar-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 2px;
        }
        
        .nav-section {
            margin-bottom: 1.25rem;
        }
        
        .nav-section:last-child {
            margin-bottom: 0;
        }
        
        .nav-section-title {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 0.625rem;
            margin-bottom: 0.5rem;
        }
        
        .nav-items {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.625rem;
            color: var(--gray-700);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .nav-item:hover {
            background: var(--gray-50);
            color: var(--gray-900);
        }
        
        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .nav-icon {
            width: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        
        .nav-label {
            flex: 1;
        }
        
        .nav-badge {
            padding: 0.125rem 0.375rem;
            font-size: 0.625rem;
            font-weight: 600;
            background: var(--gray-100);
            color: var(--gray-600);
            border-radius: 8px;
        }
        
        .nav-item.active .nav-badge {
            background: var(--primary);
            color: var(--white);
        }
        
        .nav-arrow {
            width: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.625rem;
            color: var(--gray-400);
            transition: transform 0.2s ease;
        }
        
        .nav-item.has-submenu.open .nav-arrow {
            transform: rotate(90deg);
        }
        
        .nav-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            margin-left: 1.875rem;
            margin-top: 0.125rem;
        }
        
        .nav-submenu.open {
            max-height: 400px;
        }
        
        .nav-subitem {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.625rem;
            color: var(--gray-600);
            text-decoration: none;
            font-size: 0.8125rem;
            transition: all 0.15s ease;
            border-radius: 4px;
            margin-bottom: 0.125rem;
        }
        
        .nav-subitem::before {
            content: '';
            width: 3px;
            height: 3px;
            background: var(--gray-400);
            border-radius: 50%;
        }
        
        .nav-subitem:hover {
            background: var(--gray-50);
            color: var(--gray-900);
        }
        
        .nav-subitem.active {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .nav-subitem.active::before {
            background: var(--primary);
        }
        
        .sidebar-footer {
            padding: 0.75rem 1.25rem;
            border-top: 1px solid var(--gray-200);
        }
        
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem;
            background: var(--gray-50);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .sidebar-user:hover {
            background: var(--gray-100);
        }
        
        .sidebar-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: var(--white);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }
        
        .sidebar-user-name {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .sidebar-user-role {
            font-size: 0.6875rem;
            color: var(--gray-500);
        }
        
        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
        }
        
        /* HEADER COMPACTO */
        .header {
            height: var(--header-height);
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .header-left {
            display: flex;
            align-items: center;
        }
        
        .header-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .header-search {
            position: relative;
        }
        
        .search-input {
            width: 240px;
            padding: 0.4rem 0.75rem 0.4rem 2rem;
            font-size: 0.8125rem;
            font-family: var(--font-sans);
            color: var(--gray-900);
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            outline: none;
            transition: all 0.15s ease;
        }
        
        .search-input:focus {
            background: var(--white);
            border-color: var(--gray-300);
        }
        
        .search-icon {
            position: absolute;
            left: 0.625rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.75rem;
        }
        
        .header-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            border-radius: 6px;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
        }
        
        .header-btn:hover {
            background: var(--gray-50);
            color: var(--gray-900);
        }
        
        .header-btn .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
        }
        
        /* CONTENT COMPACTO */
        .content {
            flex: 1;
            padding: 1.5rem;
        }
        
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .page-header-left {
            flex: 1;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }
        
        .page-subtitle {
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--white);
            background: var(--primary);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        /* DATATABLE MINIMALISTA */
        .table-container {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .table-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .table-filters {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .filter-input {
            padding: 0.375rem 0.625rem;
            font-size: 0.8125rem;
            font-family: var(--font-sans);
            color: var(--gray-900);
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            outline: none;
            transition: all 0.15s ease;
            width: 180px;
        }
        
        .filter-input:focus {
            background: var(--white);
            border-color: var(--gray-300);
        }
        
        .dataTables_wrapper {
            padding: 0;
        }
        
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
        
        .dataTables_info {
            padding: 0.75rem 1.25rem;
            font-size: 0.8125rem;
            color: var(--gray-600);
        }
        
        .dataTables_paginate {
            padding: 0.75rem 1.25rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.375rem;
        }
        
        table.dataTable {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: none !important;
        }
        
        table.dataTable thead {
            background: var(--gray-50);
        }
        
        table.dataTable thead th {
            background: transparent;
            color: var(--gray-700);
            font-weight: 600;
            font-size: 0.75rem;
            text-align: left;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        
        table.dataTable tbody td {
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
            color: var(--gray-900);
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }
        
        table.dataTable tbody tr {
            transition: background 0.15s ease;
        }
        
        table.dataTable tbody tr:hover {
            background: var(--gray-50);
        }
        
        table.dataTable tbody tr:last-child td {
            border-bottom: none;
        }
        
        .dataTables_paginate .paginate_button {
            padding: 0.375rem 0.625rem;
            margin: 0;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--gray-700);
            background: transparent;
            border: 1px solid var(--gray-200);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .dataTables_paginate .paginate_button:hover {
            background: var(--gray-50);
            color: var(--gray-900);
        }
        
        .dataTables_paginate .paginate_button.current {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }
        
        .dataTables_paginate .paginate_button.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        
        .user-cell {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }
        
        .user-avatar-small {
            width: 32px;
            height: 32px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .user-info {
            flex: 1;
            min-width: 0;
        }
        
        .user-name-text {
            font-weight: 500;
            color: var(--gray-900);
            font-size: 0.875rem;
        }
        
        .user-email {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.125rem;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 4px;
        }
        
        .badge::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-success::before {
            background: #10b981;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-warning::before {
            background: #f59e0b;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-danger::before {
            background: #ef4444;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.25rem;
        }
        
        .btn-action {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            border-radius: 4px;
            color: var(--gray-500);
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 0.8125rem;
        }
        
        .btn-action:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }
        
        .btn-action.view:hover {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .btn-action.edit:hover {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .btn-action.delete:hover {
            background: #fee2e2;
            color: #991b1b;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        @media (max-width: 640px) {
            .content {
                padding: 1rem;
            }
            
            .header {
                padding: 0 1rem;
            }
            
            .header-search {
                display: none;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- SIDEBAR MINIMALISTA -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-wrapper">
                    <img src="<?php echo $org['logo_url']; ?>" alt="<?php echo $org['name']; ?>" class="sidebar-logo">
                </div>
                <div class="sidebar-brand">
                    <div class="sidebar-title"><?php echo $org['name']; ?></div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <div class="nav-items">
                        <a href="dashboard.php" class="nav-item">
                            <i class="nav-icon fas fa-home"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                        
                        <div class="nav-item has-submenu active open" onclick="toggleSubmenu(this)">
                            <i class="nav-icon fas fa-users"></i>
                            <span class="nav-label">Clientes</span>
                            <span class="nav-badge">1.2K</span>
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </div>
                        <div class="nav-submenu open">
                            <a href="clientes.php" class="nav-subitem active">
                                <span>Todos los Clientes</span>
                            </a>
                            <a href="clientes-activos.php" class="nav-subitem">
                                <span>Clientes Activos</span>
                            </a>
                            <a href="prospectos.php" class="nav-subitem">
                                <span>Prospectos</span>
                            </a>
                        </div>
                        
                        <div class="nav-item has-submenu" onclick="toggleSubmenu(this)">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <span class="nav-label">Ventas</span>
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </div>
                        <div class="nav-submenu">
                            <a href="ventas.php" class="nav-subitem">
                                <span>Todas las Ventas</span>
                            </a>
                            <a href="cotizaciones.php" class="nav-subitem">
                                <span>Cotizaciones</span>
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
                        <a href="reportes.php" class="nav-item">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <span class="nav-label">Reportes</span>
                        </a>
                        <a href="tareas.php" class="nav-item">
                            <i class="nav-icon fas fa-tasks"></i>
                            <span class="nav-label">Tareas</span>
                            <span class="nav-badge">23</span>
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
                </div>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <main class="main-content">
            <header class="header">
                <div class="header-left">
                    <h1 class="header-title">Gestión de Clientes</h1>
                </div>
                
                <div class="header-right">
                    <div class="header-search">
                        <i class="search-icon fas fa-search"></i>
                        <input type="text" class="search-input" placeholder="Buscar...">
                    </div>
                    
                    <button class="header-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"></span>
                    </button>
                </div>
            </header>
            
            <div class="content">
                <div class="page-header">
                    <div class="page-header-left">
                        <h2 class="page-title">Clientes</h2>
                        <p class="page-subtitle">Gestiona todos tus contactos</p>
                    </div>
                    <button class="btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Nuevo Cliente</span>
                    </button>
                </div>
                
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Lista de Clientes</h3>
                        <div class="table-filters">
                            <input type="text" class="filter-input" id="searchTable" placeholder="Buscar...">
                        </div>
                    </div>
                    
                    <table id="clientesTable" class="display">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Empresa</th>
                                <th>Estado</th>
                                <th>Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clientes)): ?>
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr>
                                        <td>
                                            <div class="user-cell">
                                                <div class="user-avatar-small">
                                                    <?php echo strtoupper(substr($cliente['first_name'], 0, 1)); ?>
                                                </div>
                                                <div class="user-info">
                                                    <div class="user-name-text"><?php echo htmlspecialchars($cliente['last_name']); ?></div>
                                                    <div class="user-email"><?php echo htmlspecialchars($cliente['email']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($cliente['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['mobile']); ?></td>
                                        <td>
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($cliente['created_at'])); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action view" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn-action edit" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-action delete" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2.5rem;">
                                        <i class="fas fa-users" style="font-size: 2rem; color: var(--gray-300); margin-bottom: 0.75rem; display: block;"></i>
                                        <strong style="display: block; margin-bottom: 0.25rem; font-size: 0.9375rem;">No hay clientes</strong>
                                        <span style="font-size: 0.8125rem; color: var(--gray-500);">Agrega tu primer cliente</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
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
        
        $(document).ready(function() {
            var table = $('#clientesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 10,
                order: [[4, 'desc']],
                responsive: true,
                dom: 'rtip'
            });
            
            $('#searchTable').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
</body>
</html>