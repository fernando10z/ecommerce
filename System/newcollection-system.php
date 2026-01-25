<?php
session_start();
require_once 'conexion/conexion.php';
require_once __DIR__ . '/apariencia/newcollection-config.php';

// Obtener datos de la organización
$stmt = $conn->prepare("SELECT * FROM organizations LIMIT 1");
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

// Valores por defecto para comparación (Lógica Pro para los Badges)
$defaults = [
    'primary_color' => '#10b981',
    'secondary_color' => '#059669',
    'tertiary_color' => '#ffffff', 
    'logo_name' => 'CRM Pro'
];

if (!$org) {
    $org = array_merge(['name' => 'CRM Pro', 'logo_url' => 'assets/images/collab.png'], $defaults);
}

// Función helper para determinar estado (Simulación visual)
function getStatusBadge($current, $default) {
    if ($current !== $default && !empty($current)) {
        return '<span class="badge badge-active">Personalizado</span>';
    }
    return '<span class="badge badge-company">Por defecto</span>';
}

$usuario = [
    'nombre' => 'Fernando',
    'email' => 'fernando@ejemplo.com',
    'rol' => 'Administrador'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $org['logo_url']; ?>" type="image/png">
    <title>Configuración | <?php echo htmlspecialchars($org['name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-sans);
            background: var(--gray-50);
            color: var(--gray-900);
            -webkit-font-smoothing: antialiased;
        }
        
        .app-container { display: flex; min-height: 100vh; }
        .main-content { flex: 1; margin-left: var(--sidebar-width); display: flex; flex-direction: column; }

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

        /* HEADER IGUAL A CONTACTS */
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
        
        .breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--gray-600); }
        .breadcrumb-active { color: var(--gray-900); font-weight: 500; }
        
        /* PAGE CONTENT */
        .content { padding: 1.5rem; }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .page-title i { color: var(--primary); }
        
        /* CARD Y FILTROS */
        .card {
            background: var(--white);
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            background: var(--white);
        }
        
        .filters-row { display: flex; gap: 0.75rem; align-items: center; }
        
        .search-box { position: relative; }
        .search-box input {
            padding: 0.5rem 1rem 0.5rem 2.25rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            width: 240px;
            background: var(--white);
        }
        .search-box i {
            position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);
            color: var(--gray-400); font-size: 0.875rem;
        }
        
        /* TABLA ESTILO PRO */
        .table-responsive { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; }
        
        .data-table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .data-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--gray-200);
            font-size: 0.875rem;
            color: var(--gray-700);
            vertical-align: middle;
        }
        
        .data-table tbody tr:hover { background: var(--gray-50); }
        
        /* Elementos de la celda "Configuración" (como Contacto) */
        .config-cell { display: flex; align-items: center; gap: 0.75rem; }
        .config-icon {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
        }
        .config-info div:first-child { font-weight: 500; color: var(--gray-900); }
        .config-info div:last-child { font-size: 0.75rem; color: var(--gray-500); }
        
        /* Inputs dentro de la tabla */
        .table-input {
            width: 100%;
            max-width: 300px;
            padding: 0.4rem 0.6rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .table-input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px var(--primary-light); }
        
        .color-wrapper { display: flex; align-items: center; gap: 8px; }
        input[type="color"].table-input-color {
            padding: 0; border: none; width: 32px; height: 32px; cursor: pointer; background: none;
        }
        .color-code { font-family: monospace; font-size: 0.8125rem; color: var(--gray-600); background: var(--gray-100); padding: 2px 6px; border-radius: 4px; }
        
        /* Badges */
        .badge { display: inline-flex; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        .badge-active { background: #d1fae5; color: #065f46; } /* Verde para personalizado */
        .badge-company { background: #e0e7ff; color: #3730a3; } /* Azul para default */
        
        /* Acciones */
        .row-actions { display: flex; gap: 0.375rem; }
        .btn-action {
            width: 32px; height: 32px;
            border-radius: 6px; border: none;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            font-size: 0.875rem; transition: all 0.15s;
        }
        .btn-save { background: #dbeafe; color: #1d4ed8; }
        .btn-save:hover { background: #bfdbfe; color: #1e40af; }
        
        .btn-reset { background: #f3f4f6; color: #4b5563; }
        .btn-reset:hover { background: #e5e7eb; color: #1f2937; }
        
        /* Alert */
        .alert-floating {
            position: fixed; top: 1rem; right: 1rem; z-index: 100;
            padding: 1rem; border-radius: 8px; background: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary);
            display: flex; gap: 10px; align-items: center;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn { from { transform: translateX(100%); } to { transform: translateX(0); } }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
            .data-table th:nth-child(4), .data-table td:nth-child(4) { display: none; } /* Ocultar columna Estado en móvil */
        }
    </style>
</head>
<body>
    <div class="app-container">
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header class="header">
            <nav class="breadcrumb">
                <span>Inicio</span>
                <span>/</span>
                <span class="breadcrumb-active">Configuración</span>
            </nav>
        </header>
        <div class="content">
            <div class="page-header" style="margin-top: 2rem;">
                <h1 class="page-title">
                    <i class="fas fa-tshirt"></i> Apariencia: Nueva Colección
                </h1>
            </div>

            <?php if (!empty($mensaje)): ?>
                <div class="alert-floating">
                    <i class="fas fa-check-circle" style="color: var(--primary);"></i>
                    <div><?php echo $mensaje; ?></div>
                </div>
            <?php endif; ?>

            <form id="ncConfigForm" method="POST" action="" enctype="multipart/form-data">
                <div class="card">                  
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th width="30%">Elemento</th>
                                    <th width="30%">Valor</th>
                                    <th width="20%">Previsualización</th>
                                    <th width="10%">Estado</th>
                                    <th width="10%" style="text-align: right; padding-right: 1.5rem;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr style="background-color: #f9fafb;"><td colspan="5" style="padding: 0.5rem 1rem; font-weight:bold; color:var(--primary); font-size:0.8rem;">SECCIÓN HERO (SUPERIOR)</td></tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-tag"></i></div>
                                            <div class="config-info">
                                                <div>Etiqueta Temporada</div>
                                                <div>Ej: Primavera/Verano 2025</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="table-input" name="hero_label" value="<?php echo htmlspecialchars($nc_datos['hero_label'] ?? ''); ?>" placeholder="Texto pequeño superior">
                                    </td>
                                    <td><small><?php echo htmlspecialchars($nc_datos['hero_label'] ?? ''); ?></small></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_hero_label" class="btn-action btn-save" title="Guardar"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_del_hero_label" class="btn-action btn-reset" title="Borrar" style="color: #dc2626;" onclick="return confirm('¿Borrar?')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-heading"></i></div>
                                            <div class="config-info">
                                                <div>Título Principal</div>
                                                <div>Ej: Nueva Colección</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="hero_title" rows="2"><?php echo htmlspecialchars($nc_datos['hero_title'] ?? ''); ?></textarea>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($nc_datos['hero_title'] ?? ''); ?></strong></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_hero_title" class="btn-action btn-save" title="Guardar"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_del_hero_title" class="btn-action btn-reset" title="Borrar" style="color: #dc2626;" onclick="return confirm('¿Borrar?')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-align-left"></i></div>
                                            <div class="config-info">
                                                <div>Descripción Corta</div>
                                                <div>Ej: Piezas únicas...</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="hero_subtitle" rows="2"><?php echo htmlspecialchars($nc_datos['hero_subtitle'] ?? ''); ?></textarea>
                                    </td>
                                    <td><small><?php echo htmlspecialchars($nc_datos['hero_subtitle'] ?? ''); ?></small></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_hero_sub" class="btn-action btn-save" title="Guardar"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_del_hero_sub" class="btn-action btn-reset" title="Borrar" style="color: #dc2626;" onclick="return confirm('¿Borrar?')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-image"></i></div>
                                            <div class="config-info">
                                                <div>Banner Fondo</div>
                                                <div>Imagen grande superior</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="file" class="table-input" name="hero_image" accept="image/*" style="padding: 0.3rem;">
                                    </td>
                                    <td>
                                        <?php if (!empty($nc_datos['hero_image'])): ?>
                                            <img src="../images/new_collection/<?php echo htmlspecialchars($nc_datos['hero_image']); ?>" style="width: 60px; height: 30px; object-fit: cover; border-radius: 4px; border: 1px solid var(--gray-200);">
                                        <?php else: ?>
                                            <span style="font-size: 0.75rem; color: var(--gray-400);">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo !empty($nc_datos['hero_image']) ? '<span class="badge badge-active">Cargada</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_hero_img" class="btn-action btn-save" title="Subir"><i class="fas fa-upload"></i></button>
                                            <?php if (!empty($nc_datos['hero_image'])): ?>
                                            <button type="submit" name="btn_del_hero_img" class="btn-action btn-reset" title="Eliminar" style="color: #dc2626;" onclick="return confirm('¿Eliminar imagen?')"><i class="fas fa-trash"></i></button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <tr style="background-color: #f9fafb;"><td colspan="5" style="padding: 0.5rem 1rem; font-weight:bold; color:var(--primary); font-size:0.8rem;">SECCIÓN GRILLA PRODUCTOS</td></tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-layer-group"></i></div>
                                            <div class="config-info">
                                                <div>Etiqueta Sección</div>
                                                <div>Ej: Nuevas llegadas</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="table-input" name="prod_label" value="<?php echo htmlspecialchars($nc_datos['prod_label'] ?? ''); ?>" placeholder="Etiqueta grid">
                                    </td>
                                    <td><small><?php echo htmlspecialchars($nc_datos['prod_label'] ?? ''); ?></small></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_prod_label" class="btn-action btn-save" title="Guardar"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_del_prod_label" class="btn-action btn-reset" title="Borrar" style="color: #dc2626;" onclick="return confirm('¿Borrar?')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-font"></i></div>
                                            <div class="config-info">
                                                <div>Título Sección</div>
                                                <div>Ej: Lo más reciente</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="table-input" name="prod_title" value="<?php echo htmlspecialchars($nc_datos['prod_title'] ?? ''); ?>" placeholder="Título grid">
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($nc_datos['prod_title'] ?? ''); ?></strong></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_prod_title" class="btn-action btn-save" title="Guardar"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_del_prod_title" class="btn-action btn-reset" title="Borrar" style="color: #dc2626;" onclick="return confirm('¿Borrar?')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>                                       
        </main>
    </div>
    
    <script>
        // Pequeño script para simular interactividad de la UI Pro
        document.querySelectorAll('.btn-action').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Si es un botón submit real, dejamos que pase. 
                // Esto es solo para feedback visual instantáneo
                if(!this.getAttribute('onclick')) {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => this.style.transform = 'scale(1)', 150);
                }
            });
        });
    </script>
</body>
</html>