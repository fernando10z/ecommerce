<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ajusta la ruta a tu conexión si es necesario
require_once __DIR__ . '/../conexion/conexion.php';

// Función para obtener datos actuales
if (!function_exists('obtenerDatosNewColl')) {
    function obtenerDatosNewColl($conn) {
        try {
            $stmt = $conn->query("SELECT * FROM web_design_new_collection WHERE id = 1");
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }
}

$nc_datos = obtenerDatosNewColl($conn);
$mensaje = "";

/* =====================================================
   LÓGICA DE GUARDADO
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // --- SECCIÓN HERO ---

        // 1. Label Superior (Ej: Primavera/Verano)
        if (isset($_POST['btn_save_hero_label'])) {
            $val = $_POST['hero_label'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_new_collection SET hero_label = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Etiqueta Hero actualizada.";
        }
        elseif (isset($_POST['btn_del_hero_label'])) {
            $conn->query("UPDATE web_design_new_collection SET hero_label = '' WHERE id = 1");
            $mensaje = "Etiqueta Hero eliminada.";
        }

        // 2. Título Principal (Ej: Nueva Colección)
        elseif (isset($_POST['btn_save_hero_title'])) {
            $val = $_POST['hero_title'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_new_collection SET hero_title = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Título Hero actualizado.";
        }
        elseif (isset($_POST['btn_del_hero_title'])) {
            $conn->query("UPDATE web_design_new_collection SET hero_title = '' WHERE id = 1");
            $mensaje = "Título Hero eliminado.";
        }

        // 3. Subtítulo (Ej: Piezas únicas...)
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $val = $_POST['hero_subtitle'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_new_collection SET hero_subtitle = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Subtítulo actualizado.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            $conn->query("UPDATE web_design_new_collection SET hero_subtitle = '' WHERE id = 1");
            $mensaje = "Subtítulo eliminado.";
        }

        // 4. Imagen de Fondo Hero
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $extension = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, $permitidos)) {
                    // Carpeta específica para esta sección
                    $carpeta = __DIR__ . '/../../images/new_collection/';
                    if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);
                    
                    $nuevoNombre = 'nc_hero_' . time() . '.' . $extension;
                    
                    if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $carpeta . $nuevoNombre)) {
                        $stmt = $conn->prepare("UPDATE web_design_new_collection SET hero_image = ? WHERE id = 1");
                        $stmt->execute([$nuevoNombre]);
                        $mensaje = "Imagen de fondo actualizada.";
                    }
                } else {
                    $mensaje = "Error: Formato no permitido (solo jpg, png, webp).";
                }
            }
        }
        elseif (isset($_POST['btn_del_hero_img'])) {
            $conn->query("UPDATE web_design_new_collection SET hero_image = '' WHERE id = 1");
            $mensaje = "Imagen de fondo eliminada.";
        }

        // --- SECCIÓN PRODUCTOS (GRID) ---

        // 5. Label Sección Productos (Ej: Nuevas llegadas)
        elseif (isset($_POST['btn_save_prod_label'])) {
            $val = $_POST['prod_label'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_new_collection SET prod_label = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Etiqueta de productos actualizada.";
        }
        elseif (isset($_POST['btn_del_prod_label'])) {
            $conn->query("UPDATE web_design_new_collection SET prod_label = '' WHERE id = 1");
            $mensaje = "Etiqueta de productos eliminada.";
        }

        // 6. Título Sección Productos (Ej: Lo más reciente)
        elseif (isset($_POST['btn_save_prod_title'])) {
            $val = $_POST['prod_title'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_new_collection SET prod_title = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Título de productos actualizado.";
        }
        elseif (isset($_POST['btn_del_prod_title'])) {
            $conn->query("UPDATE web_design_new_collection SET prod_title = '' WHERE id = 1");
            $mensaje = "Título de productos eliminado.";
        }

        // Refrescar variables
        $nc_datos = obtenerDatosNewColl($conn);

    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>