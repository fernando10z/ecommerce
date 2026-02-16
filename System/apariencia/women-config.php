<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../conexion/conexion.php'; 

$mensaje = "";

// --- FUNCIONES DE LECTURA ---

function getWomenProducts($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM web_design_women_products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) { return []; }
}

function getWomenDesign($conn) {
    try {
        // Obtenemos la configuración global (ID 1)
        $stmt = $conn->query("SELECT * FROM web_design_women WHERE id = 1 LIMIT 1");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: [];
    } catch (PDOException $e) { return []; }
}

// Carga Inicial de Datos
$productos_list = getWomenProducts($conn);
$design_data    = getWomenDesign($conn);

/* =====================================================
   LÓGICA DE GUARDADO (POST)
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // 1. GUARDADO INDIVIDUAL: TÍTULO
        if (isset($_POST['btn_save_hero_title'])) {
            $title = $_POST['hero_title'] ?? '';
            $conn->prepare("UPDATE web_design_women SET hero_titulo = ? WHERE id = 1")->execute([$title]);
            $mensaje = "Título actualizado.";
        }

        // 2. GUARDADO INDIVIDUAL: SUBTÍTULO
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $subtitle = $_POST['hero_subtitle'] ?? '';
            $conn->prepare("UPDATE web_design_women SET hero_subtitulo = ? WHERE id = 1")->execute([$subtitle]);
            $mensaje = "Descripción actualizada.";
        }

        // 3. ELIMINACIÓN INDIVIDUAL
        elseif (isset($_POST['btn_del_hero_title'])) {
            $conn->prepare("UPDATE web_design_women SET hero_titulo = '' WHERE id = 1")->execute();
            $mensaje = "Título eliminado.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            $conn->prepare("UPDATE web_design_women SET hero_subtitulo = '' WHERE id = 1")->execute();
            $mensaje = "Descripción eliminada.";
        }

        // 4. GUARDAR TODO (GLOBAL)
        elseif (isset($_POST['btn_guardar_global'])) { 
            $title    = $_POST['hero_title'] ?? '';
            $subtitle = $_POST['hero_subtitle'] ?? '';
            
            // 1. Actualizar textos
            $conn->prepare("UPDATE web_design_women SET hero_titulo = ?, hero_subtitulo = ? WHERE id = 1")
                 ->execute([$title, $subtitle]);
            
            // 2. Procesar imagen si se sube una nueva
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImageWomen($conn, $design_data);
            }
            $mensaje = "Configuración completa guardada.";
        }

        // 5. GESTIÓN DE IMAGEN HERO
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImageWomen($conn, $design_data);
                $mensaje = "Fondo actualizado.";
            } else {
                $mensaje = "Selecciona una imagen válida.";
            }
        }
        elseif (isset($_POST['btn_del_hero_img'])) {
            if (!empty($design_data['hero_imagen'])) {
                deleteFileWomen(__DIR__ . '/../../' . $design_data['hero_imagen']);
            }
            $conn->prepare("UPDATE web_design_women SET hero_imagen = NULL WHERE id = 1")->execute();
            $mensaje = "Fondo eliminado.";
        }

        // 6. GESTIÓN DE PRODUCTOS
        elseif (isset($_POST['btn_save_product'])) {
            $name  = $_POST['prod_name'] ?? '';
            $brand = $_POST['prod_brand'] ?? '';
            $price = $_POST['prod_price'] ?? 0;
            
            if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] === 0) {
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['prod_image']['name'], PATHINFO_EXTENSION));
                
                if (in_array($ext, $permitidos)) {
                    $newName = 'women_prod_' . time() . '.' . $ext;
                    $dbPath = 'images/women/' . $newName;
                    
                    $targetDir = __DIR__ . '/../../images/women/';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    
                    if (move_uploaded_file($_FILES['prod_image']['tmp_name'], $targetDir . $newName)) {
                        $stmt = $conn->prepare("INSERT INTO web_design_women_products (nombre, marca, precio, imagen) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$name, $brand, $price, $dbPath]);
                        $mensaje = "Producto agregado correctamente.";
                    }
                } else {
                    $mensaje = "Error: Formato de imagen no permitido.";
                }
            } else {
                $mensaje = "Debes seleccionar una imagen.";
            }
        }
        elseif (isset($_POST['btn_del_product'])) {
            $id = $_POST['del_id'];
            $stmt = $conn->prepare("SELECT imagen FROM web_design_women_products WHERE id = ?");
            $stmt->execute([$id]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod) {
                deleteFileWomen(__DIR__ . '/../../' . $prod['imagen']);
                $conn->prepare("DELETE FROM web_design_women_products WHERE id = ?")->execute([$id]);
                $mensaje = "Producto eliminado.";
            }
        }

        // --- REFRESCAR DATOS ---
        $productos_list = getWomenProducts($conn);
        $design_data    = getWomenDesign($conn);

    } catch (PDOException $e) {
        $mensaje = "Error del sistema: " . $e->getMessage();
    }
}

// --- FUNCIONES AUXILIARES ---

function processHeroImageWomen($conn, $currentData) {
    $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
    $newName = 'hero_women_' . time() . '.' . $ext;
    $dbPath = 'images/women/' . $newName;
    
    // Crear directorio si no existe
    $targetDir = __DIR__ . '/../../images/women/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $targetDir . $newName)) {
        // Eliminar imagen vieja si existe
        if (!empty($currentData['hero_imagen'])) {
            deleteFileWomen(__DIR__ . '/../../' . $currentData['hero_imagen']);
        }
        $conn->prepare("UPDATE web_design_women SET hero_imagen = ? WHERE id = 1")->execute([$dbPath]);
        return true;
    }
    return false;
}

function deleteFileWomen($path) {
    if (file_exists($path)) {
        @unlink($path);
    }
}
?>