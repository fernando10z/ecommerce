<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../conexion/conexion.php';

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

// Presets de fuentes (Solo si no existe la variable)
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

// Función auxiliar (Protegida)
if (!function_exists('obtenerDatosActuales')) {
    function obtenerDatosActuales($conn) {
        try {
            $stmt = $conn->query("SELECT * FROM web_design WHERE id = 1");
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) return false;
            return $data;
        } catch (PDOException $e) {
            return false;
        }
    }
}

// Carga de datos
$datos_actuales = obtenerDatosActuales($conn);
if (!$datos_actuales) {
    $datos_actuales = [
        'logo_name' => SITE_NAME,
        'primary_color' => PRIMARY_COLOR,
        'secondary_color' => SECONDARY_COLOR,
        'tertiary_color' => TERTIARY_COLOR,
        'font_style' => FONT_STYLE,
        'top_banner_text' => BANNER,
        'footer_description' => FOOTER_DESC,
    ];
}

$mensaje_general = "";

/* =====================================================
   LÓGICA ESPECÍFICA DE INICIO (CORREGIDA)
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        // 1. GUARDADO GENERAL (Botón superior "Guardar Todo")
        if (isset($_POST['btn_guardar_global'])) {
            $sql = "UPDATE web_design SET 
                    logo_name = ?, primary_color = ?, secondary_color = ?, tertiary_color = ?, font_style = ?, top_banner_text = ?, footer_description = ? 
                    WHERE id = 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $_POST['logo_name'], $_POST['primary_color'], $_POST['secondary_color'], 
                $_POST['tertiary_color'], $_POST['font_style'], $_POST['top_banner_text'], $_POST['footer_description']
            ]);
            $mensaje_general = "¡Configuración general actualizada!";
        }

        // 2. GUARDADOS INDIVIDUALES (Nuevos)
        elseif (isset($_POST['btn_save_logo'])) {
            $sql = "UPDATE web_design SET logo_name = ? WHERE id = 1";
            $conn->prepare($sql)->execute([$_POST['logo_name']]);
            $mensaje_general = "Nombre del sitio actualizado.";
        }
        elseif (isset($_POST['btn_save_primary'])) {
            $sql = "UPDATE web_design SET primary_color = ? WHERE id = 1";
            $conn->prepare($sql)->execute([$_POST['primary_color']]);
            $mensaje_general = "Color primario actualizado.";
        }
        elseif (isset($_POST['btn_save_secondary'])) {
            $sql = "UPDATE web_design SET secondary_color = ? WHERE id = 1";
            $conn->prepare($sql)->execute([$_POST['secondary_color']]);
            $mensaje_general = "Color secundario actualizado.";
        }
        elseif (isset($_POST['btn_save_tertiary'])) {
            $sql = "UPDATE web_design SET tertiary_color = ? WHERE id = 1";
            $conn->prepare($sql)->execute([$_POST['tertiary_color']]);
            $mensaje_general = "Color terciario actualizado.";
        }
        elseif (isset($_POST['btn_save_font'])) {
            $sql = "UPDATE web_design SET font_style = ? WHERE id = 1";
            $conn->prepare($sql)->execute([$_POST['font_style']]);
            $mensaje_general = "Fuente actualizada.";
        }
        elseif (isset($_POST['btn_save_banner'])) {
            $conn->prepare("UPDATE web_design SET top_banner_text = ? WHERE id = 1")->execute([$_POST['top_banner_text']]);
            $mensaje_general = "Banner superior actualizado.";
        }
        elseif (isset($_POST['btn_save_footer_desc'])) {
            $conn->prepare("UPDATE web_design SET footer_description = ? WHERE id = 1")->execute([$_POST['footer_description']]);
            $mensaje_general = "Descripción del pie de página actualizada.";
        }

        // 3. REINICIAR TODO (Global Reset)
        elseif (isset($_POST['btn_reiniciar'])) {
            $sql = "UPDATE web_design SET logo_name=?, primary_color=?, secondary_color=?, tertiary_color=?, font_style=?, top_banner_text=?, footer_description=? WHERE id=1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([SITE_NAME, PRIMARY_COLOR, SECONDARY_COLOR, TERTIARY_COLOR, FONT_STYLE, BANNER, FOOTER_DESC]);
            $mensaje_general = "¡Valores restablecidos a fábrica!";
        }

        // 4. RESETS INDIVIDUALES (Mantienen su lógica original)
        elseif (isset($_POST['btn_reset_nombre'])) {
            $conn->prepare("UPDATE web_design SET logo_name = ? WHERE id = 1")->execute([SITE_NAME]);
            $mensaje_general = "Nombre restablecido.";
        }
        elseif (isset($_POST['btn_reset_primary'])) {
            $conn->prepare("UPDATE web_design SET primary_color = ? WHERE id = 1")->execute([PRIMARY_COLOR]);
            $mensaje_general = "Color primario restablecido.";
        }
        elseif (isset($_POST['btn_reset_secondary'])) {
            $conn->prepare("UPDATE web_design SET secondary_color = ? WHERE id = 1")->execute([SECONDARY_COLOR]);
            $mensaje_general = "Color secundario restablecido.";
        }
        elseif (isset($_POST['btn_reset_tertiary'])) {
            $conn->prepare("UPDATE web_design SET tertiary_color = ? WHERE id = 1")->execute([TERTIARY_COLOR]);
            $mensaje_general = "Color terciario restablecido.";
        }
        elseif (isset($_POST['btn_reset_font'])) {
            $conn->prepare("UPDATE web_design SET font_style = ? WHERE id = 1")->execute([FONT_STYLE]);
            $mensaje_general = "Fuente restablecida.";
        }
        elseif (isset($_POST['btn_reset_banner'])) {
             $conn->prepare("UPDATE web_design SET top_banner_text = ? WHERE id = 1")->execute([BANNER]);
             $mensaje_general = "Banner restablecido.";
        }
        elseif (isset($_POST['btn_reset_footer_desc'])) {
             $conn->prepare("UPDATE web_design SET footer_description = ? WHERE id = 1")->execute([FOOTER_DESC]);
             $mensaje_general = "Descripción restablecido.";
        }

        // Refrescar datos
        $datos_actuales = obtenerDatosActuales($conn);

    } catch (PDOException $e) {
        $mensaje_general = "Error: " . $e->getMessage();
    }
}

/* =====================================================
   ASIGNACIÓN DE VARIABLES PARA LA VISTA (IMPORTANTE)
   ===================================================== */
// Estas son las variables que usas en el CSS :root y en el HTML
$PRIMARY_COLOR   = $datos_actuales['primary_color'];
$SECONDARY_COLOR = $datos_actuales['secondary_color'];
$TERTIARY_COLOR  = $datos_actuales['tertiary_color'];
$SITE_NAME       = $datos_actuales['logo_name'];
$FONT_STYLE      = $datos_actuales['font_style'];
$TOP_BANNER_TEXT    = $datos_actuales['top_banner_text'];
$FOOTER_DESCRIPTION = $datos_actuales['footer_description'];
?>