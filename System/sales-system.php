<?php
// Inicia la sesión y carga las dependencias de conexión junto con la configuración específica de ventas
session_start();
require_once 'conexion/conexion.php';
require_once __DIR__ . '/system-configuration/sale-config.php';

// Obtiene los datos de la organización desde la base de datos para la identidad visual
$stmt = $conn->prepare("SELECT * FROM organizations LIMIT 1");
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

// Establece valores de diseño predeterminados en caso de que la tabla de organizaciones esté vacía
$defaults = [
    'primary_color' => '#10b981',
    'secondary_color' => '#059669',
    'tertiary_color' => '#ffffff', 
    'logo_name' => 'CRM Pro'
];

if (!$org) {
    $org = array_merge(['name' => 'CRM Pro', 'logo_url' => 'assets/images/collab.png'], $defaults);
}

// Mapea los datos de configuración de la promoción para prellenar los campos de la interfaz
$sale_datos = [
    'hero_title'    => $sale_design['hero_title'] ?? '',
    'hero_subtitle' => $sale_design['hero_subtitle'] ?? '',
    'hero_image'    => $sale_design['hero_image'] ?? ''
];

// Genera un elemento visual (badge) para indicar si una configuración es personalizada o por defecto
function getStatusBadge($current, $default) {
    if ($current !== $default && !empty($current)) {
        return '<span class="badge badge-active">Personalizado</span>';
    }
    return '<span class="badge badge-company">Por defecto</span>';
}

// Simula la información del usuario actual para renderizar el panel lateral (sidebar)
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
    <title>Configuración Sales | <?php echo htmlspecialchars($org['name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <?php include 'styles/styles_system.php'; ?>
</head>
<body>
    <div class="app-container">
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header class="header">
            <nav class="breadcrumb">
                <span>Inicio</span>
                <span>/</span>
                <span class="breadcrumb-active">Configuración Sale</span>
            </nav>
        </header>
        <div class="content">
            <div class="page-header" style="margin-top: 2rem;">
                <h1 class="page-title">
                    <i class="fas fa-tags"></i> Gestión de Ofertas (Sale)
                </h1>
                <div class="page-actions" style="display: flex; align-items: center; gap: 10px;">
                    <button type="submit" form="saleConfigForm" name="btn_save_global" class="btn-action btn-save" style="width: auto; padding: 0 1rem; gap: 0.5rem;" onclick="confirmarAccion(event, this)" title="Guardar TODA la configuración">
                        <i class="fas fa-save"></i> Guardar Todo
                    </button>
                </div>
            </div>

            <?php if (!empty($mensaje)): ?>
                <div class="alert-floating">
                    <i class="fas fa-check-circle" style="color: var(--primary);"></i>
                    <div><?php echo $mensaje; ?></div>
                </div>
            <?php endif; ?>

            <form id="saleConfigForm" method="POST" action="" enctype="multipart/form-data">
                
                <div class="card">                  
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th width="30%">Elemento Apariencia</th>
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
                                            <div class="config-icon"><i class="fas fa-heading"></i></div>
                                            <div class="config-info">
                                                <div>Título Principal</div>
                                                <div>Ej: SALE</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="hero_title" rows="2"><?php echo htmlspecialchars($sale_datos['hero_title'] ?? ''); ?></textarea>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($sale_datos['hero_title'] ?? ''); ?></strong></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_hero_title" class="btn-action btn-save" title="Guardar título" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_del_hero_title" class="btn-action btn-reset" title="Borrar título" style="color: #dc2626;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-align-left"></i></div>
                                            <div class="config-info">
                                                <div>Subtítulo / Info</div>
                                                <div>Ej: Hasta 50% OFF</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="hero_subtitle" rows="2"><?php echo htmlspecialchars($sale_datos['hero_subtitle'] ?? ''); ?></textarea>
                                    </td>
                                    <td><small><?php echo htmlspecialchars($sale_datos['hero_subtitle'] ?? ''); ?></small></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_hero_sub" class="btn-action btn-save" title="Guardar descripción" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_del_hero_sub" class="btn-action btn-reset" title="Borrar descripción" style="color: #dc2626;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-image"></i></div>
                                            <div class="config-info">
                                                <div>Fondo Hero</div>
                                                <div>Banner Principal</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="file" class="table-input" name="hero_image" accept="image/*" style="padding: 0.3rem;">
                                    </td>
                                    <td>
                                        <?php if (!empty($sale_datos['hero_image'])): ?>
                                            <img src="../<?php echo htmlspecialchars($sale_datos['hero_image']); ?>" style="width: 60px; height: 30px; object-fit: cover; border-radius: 4px; border: 1px solid var(--gray-200);">
                                        <?php else: ?>
                                            <span style="font-size: 0.75rem; color: var(--gray-400);">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo !empty($sale_datos['hero_image']) ? '<span class="badge badge-active">Cargada</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_hero_img" class="btn-action btn-save" title="Subir imagen" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <?php if (!empty($sale_datos['hero_image'])): ?>
                                                <button type="submit" name="btn_del_hero_img" class="btn-action btn-reset" title="Eliminar imagen" style="color: #dc2626;" onclick="confirmarAccion(event, this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </form> 
                
                <div class="card">                  
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Product Details</th>
                                    <th>Original Price</th>
                                    <th>Sale Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($productos_list)): ?>
                                    <?php foreach ($productos_list as $product): ?>
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <?php                                                 
                                                    $img_path = $product['image'] ?? $product['primary_image'] ?? null; 
                                                    ?>

                                                    <?php if (!empty($img_path)): ?>
                                                        <img src="../<?php echo htmlspecialchars($img_path); ?>" alt="Product" class="w-10 h-10 rounded-md object-cover border border-gray-200">
                                                    <?php else: ?>
                                                        <div class="w-10 h-10 rounded-md bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-gray-400 line-through">
                                                $<?php echo number_format($product['base_price'], 2); ?>
                                            </td>
                                            <td class="font-medium text-emerald-600">
                                                $<?php echo number_format($product['sale_price'], 2); ?>
                                            </td>
                                            <td>
                                                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Active</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-8 text-gray-500">
                                            No products on sale found. Go to Global Products Inventory to add some.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>                                  
    </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="javascript/sales.js"></script>

</body>
</html>