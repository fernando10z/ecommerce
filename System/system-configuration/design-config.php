<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../conexion/conexion.php';

// ID del usuario logueado (Ajusta la variable de sesión según tu sistema)
$usuario_actual_id = $_SESSION['user_id'] ?? 1;

/* =====================================================
   DEFINICIONES COMUNES (Protegidas para evitar errores)
   ===================================================== */
if (!defined('PRIMARY_COLOR')) define('PRIMARY_COLOR', '#0a0a0a');
if (!defined('SECONDARY_COLOR')) define('SECONDARY_COLOR', '#f8f7f4');
if (!defined('TERTIARY_COLOR')) define('TERTIARY_COLOR', '#b89968');
if (!defined('SITE_NAME')) define('SITE_NAME', 'ATELIER');
if (!defined('FONT_STYLE')) define('FONT_STYLE', 'elegante');
if (!defined('BANNER')) define('BANNER', 'ENVÍO GRATIS EN COMPRAS SUPERIORES — NUEVAS LLEGADAS CADA SEMANA');
if (!defined('FOOTER_DESC')) define('FOOTER_DESC', 'Redefiniendo la elegancia contemporánea desde 2026. Cada pieza es diseñada pensando en la atemporalidad.');

// Presets de fuentes
if (!isset($FONT_PRESETS)) {
    $FONT_PRESETS = [
        'elegante' => [
            'nombre'  => 'Original: Elegante (Playfair + Work Sans)',
            'url'     => 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Work+Sans:wght@300;400;500;600&display=swap',
            'heading' => "'Playfair Display', serif",
            'body'    => "'Work Sans', sans-serif"
        ],
        'moderna' => [
            'nombre'  => 'Opción 2: Moderna y Tech (Roboto)',
            'url'     => 'https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Roboto:wght@300;400;500&display=swap',
            'heading' => "'Roboto Slab', serif",
            'body'    => "'Roboto', sans-serif"
        ],
        'clasica' => [
            'nombre'  => 'Opción 3: Lectura Clásica (Merriweather)',
            'url'     => 'https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600&display=swap',
            'heading' => "'Merriweather', serif",
            'body'    => "'Open Sans', sans-serif"
        ]
    ];
}

// =====================================================
// FUNCIÓN PROFESIONAL DE AUDITORÍA (CON JSON)
// =====================================================
if (!function_exists('registrarAuditoria')) {
    function registrarAuditoria($conn, $user_id, $entity_type, $entity_id, $action, $old_values, $new_values) {
        $changed_fields = [];
        
        // Detectar cambios
        if (is_array($old_values) && is_array($new_values)) {
            foreach ($new_values as $key => $value) {
                if (!array_key_exists($key, $old_values) || $old_values[$key] !== $value) {
                    $changed_fields[] = $key;
                }
            }
        }

        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $request_id = uniqid('req_'); 

        try {
            $sql = "INSERT INTO audit_logs 
                    (organization_id, user_id, entity_type, entity_id, action, old_values, new_values, changed_fields, ip_address, user_agent, request_id, created_at) 
                    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $user_id,
                $entity_type,
                $entity_id,
                $action,
                !empty($old_values) ? json_encode($old_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($new_values) ? json_encode($new_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($changed_fields) ? json_encode($changed_fields) : null,
                $ip_address,
                $user_agent,
                $request_id
            ]);
        } catch (PDOException $e) {
            error_log("Error de auditoría: " . $e->getMessage());
        }
    }
}

// Función auxiliar
if (!function_exists('obtenerDatosActuales')) {
    function obtenerDatosActuales($conn) {
        try {
            $stmt = $conn->query("SELECT * FROM web_design WHERE id = 1");
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ?: false;
        } catch (PDOException $e) {
            return false;
        }
    }
}

// Carga de datos ANTES de procesar cambios (nuestros "old_values")
$datos_actuales = obtenerDatosActuales($conn);
if (!$datos_actuales) {
    $datos_actuales = [
        'logo_name' => SITE_NAME,
        'primary_color' => PRIMARY_COLOR,
        'secondary_color' => SECONDARY_COLOR,
        'tertiary_color' => TERTIARY_COLOR,
        'font_style' => FONT_STYLE,
        'top_banner_text' => BANNER,
        'banner_visible' => 1,
        'footer_description' => FOOTER_DESC,
    ];
}

$mensaje_general = "";

/* =====================================================
   LÓGICA ESPECÍFICA DE INICIO
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. GUARDADO GENERAL
        if (isset($_POST['btn_guardar_global'])) {
            $banner_visible = isset($_POST['banner_visible']) ? 1 : 0;
            $sql = "UPDATE web_design SET 
                    logo_name = ?, primary_color = ?, secondary_color = ?, tertiary_color = ?, font_style = ?, top_banner_text = ?, banner_visible = ?, footer_description = ? 
                    WHERE id = 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $_POST['logo_name'], $_POST['primary_color'], $_POST['secondary_color'], 
                $_POST['tertiary_color'], $_POST['font_style'], $_POST['top_banner_text'], $banner_visible, $_POST['footer_description']
            ]);
            
            // AUDITORÍA
            $nuevos_datos = [
                'logo_name' => $_POST['logo_name'], 'primary_color' => $_POST['primary_color'],
                'secondary_color' => $_POST['secondary_color'], 'tertiary_color' => $_POST['tertiary_color'],
                'font_style' => $_POST['font_style'], 'top_banner_text' => $_POST['top_banner_text'],
                'banner_visible' => $banner_visible, 'footer_description' => $_POST['footer_description']
            ];
            $viejos_datos = array_intersect_key($datos_actuales, $nuevos_datos);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'update', $viejos_datos, $nuevos_datos);
            
            $mensaje_general = "¡Configuración general actualizada!";
        }

        // 2. GUARDADOS INDIVIDUALES
        elseif (isset($_POST['btn_save_logo'])) {
            $val = $_POST['logo_name'] ?? '';
            $conn->prepare("UPDATE web_design SET logo_name = ? WHERE id = 1")->execute([$val]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'update', ['logo_name' => $datos_actuales['logo_name']], ['logo_name' => $val]);
            $mensaje_general = "Nombre del sitio actualizado.";
        }
        elseif (isset($_POST['btn_save_primary'])) {
            $val = $_POST['primary_color'] ?? '';
            $conn->prepare("UPDATE web_design SET primary_color = ? WHERE id = 1")->execute([$val]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'update', ['primary_color' => $datos_actuales['primary_color']], ['primary_color' => $val]);
            $mensaje_general = "Color primario actualizado.";
        }
        elseif (isset($_POST['btn_save_secondary'])) {
            $val = $_POST['secondary_color'] ?? '';
            $conn->prepare("UPDATE web_design SET secondary_color = ? WHERE id = 1")->execute([$val]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'update', ['secondary_color' => $datos_actuales['secondary_color']], ['secondary_color' => $val]);
            $mensaje_general = "Color secundario actualizado.";
        }
        elseif (isset($_POST['btn_save_tertiary'])) {
            $val = $_POST['tertiary_color'] ?? '';
            $conn->prepare("UPDATE web_design SET tertiary_color = ? WHERE id = 1")->execute([$val]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'update', ['tertiary_color' => $datos_actuales['tertiary_color']], ['tertiary_color' => $val]);
            $mensaje_general = "Color terciario actualizado.";
        }
        elseif (isset($_POST['btn_save_font'])) {
            $val = $_POST['font_style'] ?? '';
            $conn->prepare("UPDATE web_design SET font_style = ? WHERE id = 1")->execute([$val]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'update', ['font_style' => $datos_actuales['font_style']], ['font_style' => $val]);
            $mensaje_general = "Fuente actualizada.";
        }
        elseif (isset($_POST['btn_save_banner'])) {
            $visible = isset($_POST['banner_visible']) ? 1 : 0;
            $texto = $_POST['top_banner_text'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design SET top_banner_text = ?, banner_visible = ? WHERE id = 1");
            $stmt->execute([$texto, $visible]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'update', 
                ['top_banner_text' => $datos_actuales['top_banner_text'], 'banner_visible' => $datos_actuales['banner_visible']], 
                ['top_banner_text' => $texto, 'banner_visible' => $visible]);
            $mensaje_general = "Banner superior y visibilidad actualizados.";
        }
        elseif (isset($_POST['btn_save_footer_desc'])) {
            $val = $_POST['footer_description'] ?? '';
            $conn->prepare("UPDATE web_design SET footer_description = ? WHERE id = 1")->execute([$val]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'update', ['footer_description' => $datos_actuales['footer_description']], ['footer_description' => $val]);
            $mensaje_general = "Descripción del pie de página actualizada.";
        }

        // 3. REINICIAR TODO (Global Reset)
        elseif (isset($_POST['btn_reiniciar'])) {
            $sql = "UPDATE web_design SET logo_name=?, primary_color=?, secondary_color=?, tertiary_color=?, font_style=?, top_banner_text=?, footer_description=? WHERE id=1";
            $conn->prepare($sql)->execute([SITE_NAME, PRIMARY_COLOR, SECONDARY_COLOR, TERTIARY_COLOR, FONT_STYLE, BANNER, FOOTER_DESC]);
            
            // AUDITORÍA (Lo marcamos como 'restore')
            $nuevos_datos = [
                'logo_name' => SITE_NAME, 'primary_color' => PRIMARY_COLOR, 'secondary_color' => SECONDARY_COLOR, 
                'tertiary_color' => TERTIARY_COLOR, 'font_style' => FONT_STYLE, 'top_banner_text' => BANNER, 
                'footer_description' => FOOTER_DESC
            ];
            $viejos_datos = array_intersect_key($datos_actuales, $nuevos_datos);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'restore', $viejos_datos, $nuevos_datos);
            
            $mensaje_general = "¡Valores restablecidos a fábrica!";
        }

        // 4. RESETS INDIVIDUALES
        elseif (isset($_POST['btn_reset_nombre'])) {
            $conn->prepare("UPDATE web_design SET logo_name = ? WHERE id = 1")->execute([SITE_NAME]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'restore', ['logo_name' => $datos_actuales['logo_name']], ['logo_name' => SITE_NAME]);
            $mensaje_general = "Nombre restablecido.";
        }
        elseif (isset($_POST['btn_reset_primary'])) {
            $conn->prepare("UPDATE web_design SET primary_color = ? WHERE id = 1")->execute([PRIMARY_COLOR]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'restore', ['primary_color' => $datos_actuales['primary_color']], ['primary_color' => PRIMARY_COLOR]);
            $mensaje_general = "Color primario restablecido.";
        }
        elseif (isset($_POST['btn_reset_secondary'])) {
            $conn->prepare("UPDATE web_design SET secondary_color = ? WHERE id = 1")->execute([SECONDARY_COLOR]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'restore', ['secondary_color' => $datos_actuales['secondary_color']], ['secondary_color' => SECONDARY_COLOR]);
            $mensaje_general = "Color secundario restablecido.";
        }
        elseif (isset($_POST['btn_reset_tertiary'])) {
            $conn->prepare("UPDATE web_design SET tertiary_color = ? WHERE id = 1")->execute([TERTIARY_COLOR]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'restore', ['tertiary_color' => $datos_actuales['tertiary_color']], ['tertiary_color' => TERTIARY_COLOR]);
            $mensaje_general = "Color terciario restablecido.";
        }
        elseif (isset($_POST['btn_reset_font'])) {
            $conn->prepare("UPDATE web_design SET font_style = ? WHERE id = 1")->execute([FONT_STYLE]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'restore', ['font_style' => $datos_actuales['font_style']], ['font_style' => FONT_STYLE]);
            $mensaje_general = "Fuente restablecida.";
        }
        elseif (isset($_POST['btn_reset_banner'])) {
            $conn->prepare("UPDATE web_design SET top_banner_text = ? WHERE id = 1")->execute([BANNER]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'restore', ['top_banner_text' => $datos_actuales['top_banner_text']], ['top_banner_text' => BANNER]);
            $mensaje_general = "Banner restablecido.";
        }
        elseif (isset($_POST['btn_reset_footer_desc'])) {
            $conn->prepare("UPDATE web_design SET footer_description = ? WHERE id = 1")->execute([FOOTER_DESC]);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design', 1, 'restore', ['footer_description' => $datos_actuales['footer_description']], ['footer_description' => FOOTER_DESC]);
            $mensaje_general = "Descripción restablecida.";
        }

        // Refrescar datos para la vista
        $datos_actuales = obtenerDatosActuales($conn);

    } catch (PDOException $e) {
        $mensaje_general = "Error: " . $e->getMessage();
    }
}

/* =====================================================
   ASIGNACIÓN DE VARIABLES PARA LA VISTA (IMPORTANTE)
   ===================================================== */
$PRIMARY_COLOR   = $datos_actuales['primary_color'];
$SECONDARY_COLOR = $datos_actuales['secondary_color'];
$TERTIARY_COLOR  = $datos_actuales['tertiary_color'];
$SITE_NAME       = $datos_actuales['logo_name'];
$FONT_STYLE      = $datos_actuales['font_style'];
$TOP_BANNER_TEXT    = $datos_actuales['top_banner_text'];
$FOOTER_DESCRIPTION = $datos_actuales['footer_description'];
?>