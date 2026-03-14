<?php
// Valida la sesión activa y enlaza los controladores de base de datos y diseño para la página principal
session_start();
require_once 'conexion/conexion.php';
require_once __DIR__ . '/system-configuration/home-config.php';

// Consulta los datos de la organización para poblar los encabezados y la identidad corporativa
$stmt = $conn->prepare("SELECT * FROM organizations LIMIT 1");
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

// Configura valores de respaldo para asegurar el renderizado correcto si la base de datos está incompleta
$defaults = [
    'primary_color' => '#10b981',
    'secondary_color' => '#059669',
    'tertiary_color' => '#ffffff', 
    'logo_name' => 'CRM Pro'
];

if (!$org) {
    $org = array_merge(['name' => 'CRM Pro', 'logo_url' => 'assets/images/collab.png'], $defaults);
}

// Genera badges visuales para identificar rápidamente qué elementos de la Home han sido personalizados
function getStatusBadge($current, $default) {
    if ($current !== $default && !empty($current)) {
        return '<span class="badge badge-active">Personalizado</span>';
    }
    return '<span class="badge badge-company">Por defecto</span>';
}

// Define la estructura de datos del usuario logueado para su uso en la barra lateral y cabecera
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
                        <i class="fas fa-image"></i>
                        Apariencia de Página Home
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
                                            <div class="config-icon"><i class="fas fa-tag"></i></div>
                                            <div class="config-info">
                                                <div>Etiqueta Superior</div>
                                                <div>Texto pequeño sobre el título</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="table-input" name="hero_label" value="<?php echo htmlspecialchars($datos_actuales['hero_label'] ?? ''); ?>" placeholder="Ej: Primavera 2025">
                                    </td>
                                    <td><small><?php echo htmlspecialchars($datos_actuales['hero_label'] ?? ''); ?></small></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_label" class="btn-action btn-save" title="Guardar Etiqueta" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_delete_label" class="btn-action btn-reset" title="Borrar etiqueta" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
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
                                                <div>El encabezado grande</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="hero_title" rows="2"><?php echo htmlspecialchars($datos_actuales['hero_title'] ?? ''); ?></textarea>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($datos_actuales['hero_title'] ?? ''); ?></strong></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_title" class="btn-action btn-save" title="Guardar Título" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_delete_title" class="btn-action btn-reset" title="Borrar título" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
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
                                                <div>Descripción</div>
                                                <div>Párrafo explicativo</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="hero_subtitle" rows="3"><?php echo htmlspecialchars($datos_actuales['hero_subtitle'] ?? ''); ?></textarea>
                                    </td>
                                    <td>
                                        <small style="display:block; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                            <?php echo htmlspecialchars($datos_actuales['hero_subtitle'] ?? ''); ?>
                                        </small>
                                    </td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_desc" class="btn-action btn-save" title="Guardar Descripción" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_delete_desc" class="btn-action btn-reset" title="Borrar descripción" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
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
                                                <div>Imagen de Fondo</div>
                                                <div>Hero section</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="file" class="table-input" name="hero_image" accept="image/*" style="padding: 0.3rem;">
                                    </td>
                                    <td>
                                        <?php if (!empty($datos_actuales['image_background'])): ?>
                                            <img src="../images/home/<?php echo htmlspecialchars($datos_actuales['image_background']); ?>" style="width: 48px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid var(--gray-200);">
                                        <?php else: ?>
                                            <span style="font-size: 0.75rem; color: var(--gray-400);">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo !empty($datos_actuales['image_background']) ? '<span class="badge badge-active">Cargada</span>' : '<span class="badge badge-company">Vacío</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_image" class="btn-action btn-save" title="Subir imagen" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            
                                            <?php if (!empty($datos_actuales['image_background'])): ?>
                                            <button type="submit" name="btn_delete_image" class="btn-action btn-reset" title="Eliminar imagen" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-female"></i></div>
                                            <div class="config-info">
                                                <div>Categoría Mujer</div>
                                                <div>Imagen tarjeta grande</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="file" class="table-input" name="image_woman" accept="image/*" style="padding: 0.3rem;">
                                    </td>
                                    <td>
                                        <?php if (!empty($datos_actuales['image_woman'])): ?>
                                            <img src="../images/home/<?php echo htmlspecialchars($datos_actuales['image_woman']); ?>" style="width: 48px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid var(--gray-200);">
                                        <?php else: ?>
                                            <span style="font-size: 0.75rem; color: var(--gray-400);">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo !empty($datos_actuales['image_woman']) ? '<span class="badge badge-active">Cargada</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_woman" class="btn-action btn-save" title="Subir imagen" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <?php if (!empty($datos_actuales['image_woman'])): ?>
                                            <button type="submit" name="btn_delete_woman" class="btn-action btn-reset" title="Eliminar imagen" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-male"></i></div>
                                            <div class="config-info">
                                                <div>Categoría Hombre</div>
                                                <div>Imagen tarjeta pequeña</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="file" class="table-input" name="image_man" accept="image/*" style="padding: 0.3rem;">
                                    </td>
                                    <td>
                                        <?php if (!empty($datos_actuales['image_man'])): ?>
                                            <img src="../images/home/<?php echo htmlspecialchars($datos_actuales['image_man']); ?>" style="width: 48px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid var(--gray-200);">
                                        <?php else: ?>
                                            <span style="font-size: 0.75rem; color: var(--gray-400);">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo !empty($datos_actuales['image_man']) ? '<span class="badge badge-active">Cargada</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_man" class="btn-action btn-save" title="Subir imagen" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <?php if (!empty($datos_actuales['image_man'])): ?>
                                            <button type="submit" name="btn_delete_man" class="btn-action btn-reset" title="Eliminar imagen" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-percent"></i></div>
                                            <div class="config-info">
                                                <div>Categoría Sale</div>
                                                <div>Imagen ofertas</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="file" class="table-input" name="image_sale" accept="image/*" style="padding: 0.3rem;">
                                    </td>
                                    <td>
                                        <?php if (!empty($datos_actuales['image_sale'])): ?>
                                            <img src="../images/home/<?php echo htmlspecialchars($datos_actuales['image_sale']); ?>" style="width: 48px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid var(--gray-200);">
                                        <?php else: ?>
                                            <span style="font-size: 0.75rem; color: var(--gray-400);">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo !empty($datos_actuales['image_sale']) ? '<span class="badge badge-active">Cargada</span>' : '<span class="badge badge-company">Default</span>'; ?></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_sale" class="btn-action btn-save" title="Subir imagen" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <?php if (!empty($datos_actuales['image_sale'])): ?>
                                            <button type="submit" name="btn_delete_sale" class="btn-action btn-reset" title="Eliminar imagen" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="config-cell">
                                            <div class="config-icon"><i class="fas fa-tag"></i></div>
                                            <div class="config-info">
                                                <div>Etiqueta Categoría</div>
                                                <div>Texto pequeño sobre el título</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="table-input" name="cat_label" value="<?php echo htmlspecialchars($datos_actuales['cat_label'] ?? ''); ?>" placeholder="Ej: Primavera 2025">
                                    </td>
                                    <td><small><?php echo htmlspecialchars($datos_actuales['cat_label'] ?? ''); ?></small></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_cat_label" class="btn-action btn-save" title="Guardar Etiqueta" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_delete_cat_label" class="btn-action btn-reset" title="Borrar etiqueta" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
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
                                                <div>Título Categoría</div>
                                                <div>El encabezado grande</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="cat_title" rows="2"><?php echo htmlspecialchars($datos_actuales['cat_title'] ?? ''); ?></textarea>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($datos_actuales['cat_title'] ?? ''); ?></strong></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_cat_title" class="btn-action btn-save" title="Guardar Título" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_delete_cat_title" class="btn-action btn-reset" title="Borrar título" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
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
                                                <div>Título Noticias</div>
                                                <div>El encabezado grande</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="news_title" rows="2"><?php echo htmlspecialchars($datos_actuales['news_title'] ?? ''); ?></textarea>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($datos_actuales['news_title'] ?? ''); ?></strong></td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_news_title" class="btn-action btn-save" title="Guardar Título" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_delete_news_title" class="btn-action btn-reset" title="Borrar título" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
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
                                                <div>Descripción Noticias</div>
                                                <div>Párrafo explicativo</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="table-input" name="news_subtitle" rows="3"><?php echo htmlspecialchars($datos_actuales['news_subtitle'] ?? ''); ?></textarea>
                                    </td>
                                    <td>
                                        <small style="display:block; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                            <?php echo htmlspecialchars($datos_actuales['news_subtitle'] ?? ''); ?>
                                        </small>
                                    </td>
                                    <td><span class="badge badge-active">Texto</span></td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="submit" name="btn_save_news_desc" class="btn-action btn-save" title="Guardar Descripción" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="submit" name="btn_delete_news_desc" class="btn-action btn-reset" title="Borrar descripción" style="color: #dc2626; background: #fee2e2;" onclick="confirmarAccion(event, this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>    
                    </div>
                </div>  
                
            </form> </div>
    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="javascript/home.js"></script>
</body>
</html>