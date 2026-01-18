<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexion/conexion.php'; // Usa $conn (PDO)

/* =====================================================
   PASO 0: DEFINIR CONSTANTES GLOBALES (SITE_NAME)
   ===================================================== */
if (!defined('SITE_NAME')) {
    try {
        $stmtSite = $conn->query("SELECT logo_name FROM web_design WHERE id = 1");
        $siteData = $stmtSite->fetch(PDO::FETCH_ASSOC);

        if ($siteData && !empty($siteData['logo_name'])) {
            define('SITE_NAME', $siteData['logo_name']);
        } else {
            define('SITE_NAME', 'ATELIER');
        }
    } catch (PDOException $e) {
        define('SITE_NAME', 'ATELIER');
    }
}

/* =====================================================
   PASO 1: PROCESAR EL FORMULARIO
   ===================================================== */
if (isset($_POST['btn_guardar'])) {

    $nuevo_nombre = $_POST['logo_name'];
    $color_pri    = $_POST['primary_color'];
    $color_sec    = $_POST['secondary_color'];
    $color_accent = $_POST['accent_color'];

    try {
        $sql_update = "UPDATE web_design SET 
                        logo_name = ?,
                        primary_color = ?,
                        secondary_color = ?,
                        accent_color = ?
                    WHERE id = 1";

        $stmt = $conn->prepare($sql_update);
        $stmt->execute([
            $nuevo_nombre,
            $color_pri,
            $color_sec,
            $color_accent
        ]);

        // 🔄 Actualizar constante en la misma ejecución
        if (defined('SITE_NAME')) {
            // PHP no permite redefinir constantes, pero ya quedó guardado para próximas cargas
        }

        $mensaje = "¡Cambios guardados correctamente!";

    } catch (PDOException $e) {
        $mensaje = "Error al guardar: " . $e->getMessage();
    }
}

/* =====================================================
   PASO 2: CONSULTAR DATOS ACTUALES
   ===================================================== */
try {
    $stmt = $conn->query("SELECT * FROM web_design WHERE id = 1");
    $datos_actuales = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $datos_actuales = false;
}

if (!$datos_actuales) {
    $datos_actuales = [
        'logo_name' => '',
        'primary_color' => '#000000',
        'secondary_color' => '#000000',
        'accent_color' => '#b89968'
    ];
}

// =====================================================
// PASO 3: DEFINIR COLORES LISTOS PARA USAR EN EL SITIO
// =====================================================
$PRIMARY_COLOR   = $datos_actuales['primary_color']   ?? '#0a0a0a';
$SECONDARY_COLOR = $datos_actuales['secondary_color'] ?? '#f8f7f4';
$ACCENT_COLOR    = $datos_actuales['accent_color']    ?? '#b89968';
?>