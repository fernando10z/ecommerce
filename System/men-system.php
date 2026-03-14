<?php
// Inicializa la sesión y carga las dependencias de base de datos y configuración para la sección masculina
session_start();
require_once 'conexion/conexion.php';
require_once __DIR__ . '/system-configuration/men-config.php';

// Consulta los datos generales de la organización para personalizar el branding de la interfaz
$stmt = $conn->prepare("SELECT * FROM organizations LIMIT 1");
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

// Define estilos y recursos visuales por defecto en caso de que la organización no tenga datos configurados
$defaults = [
    'primary_color' => '#10b981',
    'secondary_color' => '#059669',
    'tertiary_color' => '#ffffff', 
    'logo_name' => 'CRM Pro'
];

if (!$org) {
    $org = array_merge(['name' => 'CRM Pro', 'logo_url' => 'assets/images/collab.png'], $defaults);
}

// Mapea los valores obtenidos del archivo de configuración hacia variables locales para la sección Hero
$men_data = [
    'hero_title'    => $design_data['hero_titulo'] ?? '',
    'hero_subtitle' => $design_data['hero_subtitulo'] ?? '',
    'hero_image'    => $design_data['hero_imagen'] ?? ''
];

// Genera un distintivo visual que indica si un campo mantiene su valor original o ha sido modificado
function getStatusBadge($current, $default) {
    if ($current !== $default && !empty($current)) {
        return '<span class="badge badge-active">Personalizado</span>';
    }
    return '<span class="badge badge-company">Por defecto</span>';
}

// Establece los datos del usuario administrativo para su visualización en los componentes de navegación
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
    <title>Configuración Men | <?php echo htmlspecialchars($org['name']); ?></title>
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
                <span class="breadcrumb-active">Configuración Men</span>
            </nav>
        </header>
        <div class="content">
            <div class="page-header" style="margin-top: 2rem;">
                <h1 class="page-title">
                    <i class="fas fa-tshirt"></i> Gestión de Productos Hombre
                </h1>
                <div class="page-actions" style="display: flex; align-items: center; gap: 10px;">
                    <button type="submit" form="menConfigForm" name="btn_save_global" class="btn-action btn-save" style="width: auto; padding: 0 1rem; gap: 0.5rem;" onclick="confirmarAccion(event, this)" title="Guardar TODA la configuración">
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

            <form id="menConfigForm" method="POST" action="" enctype="multipart/form-data">
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
                                                <div>Ej: Colección Hombre</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="hero_title" rows="2"><?php echo htmlspecialchars($men_data['hero_title'] ?? ''); ?></textarea>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($men_data['hero_title'] ?? ''); ?></strong></td>
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
                                                <div>Descripción Corta</div>
                                                <div>Ej: Estilo contemporáneo...</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="hero_subtitle" rows="2"><?php echo htmlspecialchars($men_data['hero_subtitle'] ?? ''); ?></textarea>
                                    </td>
                                    <td><small><?php echo htmlspecialchars($men_data['hero_subtitle'] ?? ''); ?></small></td>
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
                                                <div>Banner Fondo</div>
                                                <div>Imagen grande superior</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="file" class="table-input" name="hero_image" accept="image/*" style="padding: 0.3rem;">
                                    </td>
                                    <td>
                                        <?php if (!empty($men_data['hero_image'])): ?>
                                            <img src="../<?php echo htmlspecialchars($men_data['hero_image']); ?>" style="width: 60px; height: 30px; object-fit: cover; border-radius: 4px; border: 1px solid var(--gray-200);">
                                        <?php else: ?>
                                            <span style="font-size: 0.75rem; color: var(--gray-400);">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo !empty($men_data['hero_image']) ? '<span class="badge badge-active">Cargada</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_hero_img" class="btn-action btn-save" title="Subir imagen" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <?php if (!empty($men_data['hero_image'])): ?>
                                            <button type="submit" name="btn_del_hero_img" class="btn-action btn-reset" title="Eliminar imagen" style="color: #dc2626;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
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
                                <th>Price</th>
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
                                        <td class="font-medium">
                                            <?php if (!empty($product['sale_price']) && $product['sale_price'] > 0): ?>
                                                <span class="text-emerald-600 font-bold">$<?php echo number_format($product['sale_price'], 2); ?></span>
                                                <br>
                                                <span class="text-gray-400 line-through text-xs">$<?php echo number_format($product['base_price'], 2); ?></span>
                                            <?php else: ?>
                                                <span class="text-gray-900">$<?php echo number_format($product['base_price'], 2); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Active</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-gray-500">
                                        No products found in this section. Go to Global Products Inventory to add some.
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

    <script src="javascript/men.js"></script>
</body>
</html>