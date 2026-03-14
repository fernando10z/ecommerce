<?php
// Arranca la sesión y vincula la lógica de conexión junto a la configuración de la Nueva Colección
session_start();
require_once 'conexion/conexion.php';
require_once __DIR__ . '/system-configuration/newcollection-config.php';

// Ejecuta la recuperación de metadatos de la organización para el renderizado del frontend
$stmt = $conn->prepare("SELECT * FROM organizations LIMIT 1");
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

// Define parámetros visuales estándar para asegurar la consistencia si faltan datos en la base
$defaults = [
    'primary_color' => '#10b981',
    'secondary_color' => '#059669',
    'tertiary_color' => '#ffffff', 
    'logo_name' => 'CRM Pro'
];

if (!$org) {
    $org = array_merge(['name' => 'CRM Pro', 'logo_url' => 'assets/images/collab.png'], $defaults);
}

// Función encargada de validar cambios en tiempo real para mostrar etiquetas de estado en la configuración
function getStatusBadge($current, $default) {
    if ($current !== $default && !empty($current)) {
        return '<span class="badge badge-active">Personalizado</span>';
    }
    return '<span class="badge badge-company">Por defecto</span>';
}

// Inicializa el perfil del usuario autenticado para la gestión de permisos en la vista
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
                <span class="breadcrumb-active">Configuración</span>
            </nav>
        </header>
        <div class="content">
            <div class="page-header" style="margin-top: 2rem;">
                <h1 class="page-title">
                    <i class="fas fa-tshirt"></i> Apariencia: Nueva Colección
                </h1>
                <div class="page-actions" style="display: flex; align-items: center; gap: 10px;">
                    <button type="submit" form="configForm" name="btn_guardar_global" class="btn-action btn-save" style="width: auto; padding: 0 1rem; gap: 0.5rem;" onclick="confirmarAccion(event, this)" title="Guardar TODA la configuración">
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

            <form id="configForm" method="POST" action="" enctype="multipart/form-data">
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
                                            <button type="submit" name="btn_save_hero_label" class="btn-action btn-save" title="Guardar etiqueta" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_del_hero_label" class="btn-action btn-reset" title="Borrar etiqueta" style="color: #dc2626;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
                                        <?php if (!empty($nc_datos['hero_image'])): ?>
                                            <img src="../images/new_collection/<?php echo htmlspecialchars($nc_datos['hero_image']); ?>" style="width: 60px; height: 30px; object-fit: cover; border-radius: 4px; border: 1px solid var(--gray-200);">
                                        <?php else: ?>
                                            <span style="font-size: 0.75rem; color: var(--gray-400);">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo !empty($nc_datos['hero_image']) ? '<span class="badge badge-active">Cargada</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_hero_img" class="btn-action btn-save" title="Subir imagen" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <?php if (!empty($nc_datos['hero_image'])): ?>
                                            <button type="submit" name="btn_del_hero_img" class="btn-action btn-reset" title="Eliminar imagen" style="color: #dc2626;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
                                            <button type="submit" name="btn_save_prod_label" class="btn-action btn-save" title="Guardar etiqueta" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_del_prod_label" class="btn-action btn-reset" title="Borrar etiqueta" style="color: #dc2626;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
                                            <button type="submit" name="btn_save_prod_title" class="btn-action btn-save" title="Guardar título" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_del_prod_title" class="btn-action btn-reset" title="Borrar título" style="color: #dc2626;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="javascript/newcollection.js"></script>
    
</body>
</html>