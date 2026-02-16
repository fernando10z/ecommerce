<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../conexion/conexion.php'; 

$message = "";

// --- READ FUNCTIONS ---

function getMenProducts($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM web_design_men_products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) { return []; }
}

function getMenDesign($conn) {
    try {
        // Fetching global config (ID 1)
        $stmt = $conn->query("SELECT * FROM web_design_men WHERE id = 1 LIMIT 1");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: [];
    } catch (PDOException $e) { return []; }
}

// Initial Data Load
$products_list = getMenProducts($conn);
$design_data   = getMenDesign($conn);

/* =====================================================
   SAVE LOGIC (POST)
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // ---------------------------------------------------
        // 1. INDIVIDUAL SAVE: TITLE
        // ---------------------------------------------------
        if (isset($_POST['btn_save_hero_title'])) {
            $title = $_POST['hero_title'] ?? '';
            // Note: DB column 'hero_titulo' remains in Spanish to match DB
            $conn->prepare("UPDATE web_design_men SET hero_titulo = ? WHERE id = 1")->execute([$title]);
            $message = "Título actualizado.";
        }

        // ---------------------------------------------------
        // 2. INDIVIDUAL SAVE: SUBTITLE
        // ---------------------------------------------------
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $subtitle = $_POST['hero_subtitle'] ?? '';
            $conn->prepare("UPDATE web_design_men SET hero_subtitulo = ? WHERE id = 1")->execute([$subtitle]);
            $message = "Subtítulo actualizado.";
        }

        // ---------------------------------------------------
        // 3. INDIVIDUAL DELETE
        // ---------------------------------------------------
        elseif (isset($_POST['btn_del_hero_title'])) {
            $conn->prepare("UPDATE web_design_men SET hero_titulo = '' WHERE id = 1")->execute();
            $message = "Título eliminado.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            $conn->prepare("UPDATE web_design_men SET hero_subtitulo = '' WHERE id = 1")->execute();
            $message = "Subtítulo eliminado.";
        }

        // ---------------------------------------------------
        // 4. SAVE ALL (GLOBAL)
        // ---------------------------------------------------
        elseif (isset($_POST['btn_save_global'])) { // Renamed from btn_guardar_global
            $title    = $_POST['hero_title'] ?? '';
            $subtitle = $_POST['hero_subtitle'] ?? '';
            
            // 1. Update texts
            $conn->prepare("UPDATE web_design_men SET hero_titulo = ?, hero_subtitulo = ? WHERE id = 1")
                 ->execute([$title, $subtitle]);
            
            // 2. Process image if a new one is uploaded
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImage($conn, $design_data);
            }
            $message = "Configuración completa guardada.";
        }

        // ---------------------------------------------------
        // 5. HERO IMAGE MANAGEMENT
        // ---------------------------------------------------
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImage($conn, $design_data);
                $message = "Imagen de fondo actualizada.";
            } else {
                $message = "Selecciona una imagen válida.";
            }
        }
        elseif (isset($_POST['btn_del_hero_img'])) {
            if (!empty($design_data['hero_imagen'])) {
                deleteFile(__DIR__ . '/../../' . $design_data['hero_imagen']);
            }
            $conn->prepare("UPDATE web_design_men SET hero_imagen = NULL WHERE id = 1")->execute();
            $message = "Imagen de fondo eliminada.";
        }

        // ---------------------------------------------------
        // 6. PRODUCT MANAGEMENT
        // ---------------------------------------------------
        elseif (isset($_POST['btn_save_product'])) {
            $name  = $_POST['prod_name'] ?? '';
            $brand = $_POST['prod_brand'] ?? '';
            $price = $_POST['prod_price'] ?? 0;
            
            if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] === 0) {
                $ext = strtolower(pathinfo($_FILES['prod_image']['name'], PATHINFO_EXTENSION));
                $newName = 'men_prod_' . time() . '.' . $ext;
                $dbPath = 'images/men/' . $newName;
                
                if (move_uploaded_file($_FILES['prod_image']['tmp_name'], __DIR__ . '/../../' . $dbPath)) {
                    $stmt = $conn->prepare("INSERT INTO web_design_men_products (nombre, marca, precio, imagen) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$name, $brand, $price, $dbPath]);
                    $message = "Producto agregado.";
                }
            }
        }
        elseif (isset($_POST['btn_del_product'])) {
            $id = $_POST['del_id'];
            $stmt = $conn->prepare("SELECT imagen FROM web_design_men_products WHERE id = ?");
            $stmt->execute([$id]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod) {
                deleteFile(__DIR__ . '/../../' . $prod['imagen']);
                $conn->prepare("DELETE FROM web_design_men_products WHERE id = ?")->execute([$id]);
                $message = "Producto eliminado.";
            }
        }

        // --- REFRESH DATA ---
        $products_list = getMenProducts($conn);
        $design_data   = getMenDesign($conn);

    } catch (PDOException $e) {
        $message = "Error del sistema: " . $e->getMessage();
    }
}

// --- HELPER FUNCTIONS ---

function processHeroImage($conn, $currentData) {
    $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
    $newName = 'hero_men_' . time() . '.' . $ext;
    $dbPath = 'images/men/' . $newName;
    
    // Create directory if not exists
    $targetDir = __DIR__ . '/../../images/men/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $targetDir . $newName)) {
        // Delete old image if exists
        if (!empty($currentData['hero_imagen'])) {
            deleteFile(__DIR__ . '/../../' . $currentData['hero_imagen']);
        }
        $conn->prepare("UPDATE web_design_men SET hero_imagen = ? WHERE id = 1")->execute([$dbPath]);
        return true;
    }
    return false;
}

function deleteFile($path) {
    if (file_exists($path)) {
        @unlink($path);
    }
}
?>