<?php
// Inicia la sesión de usuario y carga la conexión junto con la configuración global de diseño
session_start();
require_once 'conexion/conexion.php';
require_once __DIR__ . '/system-configuration/design-config.php';

// Recupera los metadatos de la organización para aplicar el branding dinámico en el panel
$stmt = $conn->prepare("SELECT * FROM organizations LIMIT 1");
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

// Define los valores de diseño estándar para comparar cambios y gestionar los badges de estado
$defaults = [
    'primary_color' => '#10b981',
    'secondary_color' => '#059669',
    'tertiary_color' => '#ffffff', 
    'logo_name' => 'CRM Pro'
];

if (!$org) {
    $org = array_merge(['name' => 'CRM Pro', 'logo_url' => 'assets/images/collab.png'], $defaults);
}

// Evalúa si un valor ha sido modificado respecto al original para mostrar una etiqueta visual de personalización
function getStatusBadge($current, $default) {
    if ($current !== $default && !empty($current)) {
        return '<span class="badge badge-active">Personalizado</span>';
    }
    return '<span class="badge badge-company">Por defecto</span>';
}

// Establece la identidad del administrador actual para la renderización del menú lateral
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
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-sliders-h"></i>
                    Apariencia del Sistema
                </h1>
                <div class="page-actions" style="display: flex; align-items: center; gap: 10px;">
                    <button type="submit" form="configForm" name="btn_guardar_global" class="btn-action btn-save" style="width: auto; padding: 0 1rem; gap: 0.5rem;" onclick="confirmarAccion(event, this)" title="Guardar TODA la configuración">
                        <i class="fas fa-save"></i> Guardar Todo
                    </button>
                    
                    <button type="submit" form="configForm" name="btn_reiniciar" class="btn-action btn-save" style="width: auto; padding: 0 1rem; gap: 0.5rem; background-color: #fee2e2; color: #dc2626;" onclick="confirmarAccion(event, this)" title="Reiniciar TODA la configuración">
                        <i class="fas fa-undo"></i> Reiniciar Todo
                    </button>
                </div>
            </div>

            <?php if (!empty($mensaje_general)): ?>
                <div class="alert-floating">
                    <i class="fas fa-check-circle" style="color: var(--primary);"></i>
                    <div><?php echo $mensaje_general; ?></div>
                </div>
            <?php endif; ?>
            
            <form id="configForm" method="POST" action="" enctype="multipart/form-data">
                
                <div class="card">                  
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th width="30%">Configuración</th>
                                    <th width="30%">Valor</th>
                                    <th width="20%">Previsualización</th>
                                    <th width="10%">Estado</th>
                                    <th width="10%" style="text-align: right; padding-right: 1.5rem;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-globe"></i></div>
                                            <div class="config-info">
                                                <div>Nombre del Sitio</div>
                                                <div>Visible en pestaña y login</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="table-input" name="logo_name" value="<?php echo htmlspecialchars($datos_actuales['logo_name']); ?>">
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; font-size: 0.9rem; color: var(--gray-900);">
                                            <?php echo htmlspecialchars($datos_actuales['logo_name']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo ($datos_actuales['logo_name'] !== SITE_NAME) ? '<span class="badge badge-active">Personalizado</span>' : '<span class="badge badge-company">Default</span>'; ?>
                                    </td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_logo" class="btn-action btn-save" title="Guardar cambios" onclick="confirmarAccion(event, this)"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_reset_nombre" class="btn-action btn-reset" title="Restablecer nombre" onclick="confirmarAccion(event, this)"><i class="fas fa-undo"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                                <i class="fas fa-palette"></i>
                                            </div>
                                            <div class="config-info">
                                                <div>Color Primario</div>
                                                <div>Botones principales</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="color-wrapper">
                                            <input type="color" class="table-input-color" name="primary_color" value="<?php echo $datos_actuales['primary_color']; ?>">
                                            <span class="color-code"><?php echo $datos_actuales['primary_color']; ?></span>
                                        </div>
                                    </td>
                                    <td><div style="width: 100%; height: 24px; border-radius: 4px; background: <?php echo $datos_actuales['primary_color']; ?>;"></div></td>
                                    <td><?php echo ($datos_actuales['primary_color'] !== PRIMARY_COLOR) ? '<span class="badge badge-active">Personalizado</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_primary" class="btn-action btn-save" title="Guardar cambios" onclick="confirmarAccion(event, this)"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_reset_primary" class="btn-action btn-reset" title="Restablecer nombre" onclick="confirmarAccion(event, this)"><i class="fas fa-undo"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-tint"></i></div>
                                            <div class="config-info">
                                                <div>Color Secundario</div>
                                                <div>Estados hover</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="color-wrapper">
                                            <input type="color" class="table-input-color" name="secondary_color" value="<?php echo $datos_actuales['secondary_color']; ?>">
                                            <span class="color-code"><?php echo $datos_actuales['secondary_color']; ?></span>
                                        </div>
                                    </td>
                                    <td><div style="width: 100%; height: 24px; border-radius: 4px; background: <?php echo $datos_actuales['secondary_color']; ?>;"></div></td>
                                    <td><?php echo ($datos_actuales['secondary_color'] !== SECONDARY_COLOR) ? '<span class="badge badge-active">Personalizado</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_secondary" class="btn-action btn-save" title="Guardar cambios" onclick="confirmarAccion(event, this)"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_reset_secondary" class="btn-action btn-reset" title="Restablecer nombre" onclick="confirmarAccion(event, this)"><i class="fas fa-undo"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-brush"></i></div>
                                            <div class="config-info">
                                                <div>Color Terciario</div>
                                                <div>Acentos</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="color-wrapper">
                                            <input type="color" class="table-input-color" name="tertiary_color" value="<?php echo $datos_actuales['tertiary_color']; ?>">
                                            <span class="color-code"><?php echo $datos_actuales['tertiary_color']; ?></span>
                                        </div>
                                    </td>
                                    <td><div style="width: 100%; height: 24px; border-radius: 4px; background: <?php echo $datos_actuales['tertiary_color']; ?>;"></div></td>
                                    <td><?php echo ($datos_actuales['tertiary_color'] !== TERTIARY_COLOR) ? '<span class="badge badge-active">Personalizado</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_tertiary" class="btn-action btn-save" title="Guardar cambios" onclick="confirmarAccion(event, this)"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_reset_tertiary" class="btn-action btn-reset" title="Restablecer nombre" onclick="confirmarAccion(event, this)"><i class="fas fa-undo"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-font"></i></div>
                                            <div class="config-info">
                                                <div>Tipografía</div>
                                                <div>Estilo general de letra</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <select name="font_style" class="table-input" style="padding: 0.5rem; cursor: pointer;">
                                            <?php foreach ($FONT_PRESETS as $key => $font): ?>
                                                <option value="<?php echo $key; ?>" <?php echo ($datos_actuales['font_style'] === $key) ? 'selected' : ''; ?>>
                                                    <?php echo $font['nombre']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.85rem; color: var(--gray-500);">
                                            Opción actual: <strong><?php echo ucfirst($datos_actuales['font_style']); ?></strong>
                                        </div>
                                    </td>
                                    <td><?php echo ($datos_actuales['font_style'] !== 'elegante') ? '<span class="badge badge-active">Personalizado</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_font" class="btn-action btn-save" title="Guardar cambios" onclick="confirmarAccion(event, this)"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_reset_font" class="btn-action btn-reset" title="Restablecer nombre" onclick="confirmarAccion(event, this)"><i class="fas fa-undo"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-bullhorn"></i></div>
                                            <div class="config-info">
                                                <div>Banner Superior</div>
                                                <div>Barra de anuncios top</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="table-input" name="top_banner_text" value="<?php echo htmlspecialchars($datos_actuales['top_banner_text'] ?? ''); ?>">
                                    </td>
                                    <td>
                                        <small style="display:block; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                            <?php echo htmlspecialchars($datos_actuales['top_banner_text'] ?? ''); ?>
                                        </small>
                                    </td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end; display: flex; align-items: center;">
                                            
                                            <input type="checkbox" id="chk_banner_visible" name="banner_visible" value="1" style="display: none;"
                                                <?php echo (isset($datos_actuales['banner_visible']) && $datos_actuales['banner_visible'] == 1) ? 'checked' : ''; ?>>

                                            <button type="button" id="btn_toggle_eye" class="btn-action" style="margin-right: 10px;" onclick="toggleBanner(event)" title="Cambiar visibilidad">
                                                <?php if (isset($datos_actuales['banner_visible']) && $datos_actuales['banner_visible'] == 1): ?>
                                                    <i class="fas fa-eye" style="color: green;"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-eye-slash" style="color: red;"></i>
                                                <?php endif; ?>
                                            </button>

                                            <button type="submit" name="btn_save_banner" class="btn-action btn-save" title="Guardar cambios" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            
                                            <button type="submit" name="btn_reset_banner" class="btn-action btn-reset" title="Restablecer" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-align-left"></i></div>
                                            <div class="config-info">
                                                <div>Footer: Descripción</div>
                                                <div>Texto sobre la marca</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="footer_description" rows="2"><?php echo htmlspecialchars($datos_actuales['footer_description'] ?? ''); ?></textarea>
                                    </td>
                                    <td>
                                        <small style="display:block; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                            <?php echo htmlspecialchars($datos_actuales['footer_description'] ?? ''); ?>
                                        </small>
                                    </td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_footer_desc" class="btn-action btn-save" title="Guardar cambios" onclick="confirmarAccion(event, this)"><i class="fas fa-save"></i></button>
                                            <button type="submit" name="btn_reset_footer_desc" class="btn-action btn-reset" title="Restablecer nombre" onclick="confirmarAccion(event, this)"><i class="fas fa-undo"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="javascript/design.js"></script>
</body>
</html>