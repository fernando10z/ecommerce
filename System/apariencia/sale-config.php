<?php
// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir conexión a la base de datos
require_once __DIR__ . '/../conexion/conexion.php'; 

$message = "";

// --- FUNCIONES DE LECTURA ---

// Función para obtener la lista de productos de oferta (orden descendente)
function getSaleProducts($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM web_design_sale_products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) { return []; }
}

// Función para obtener la configuración visual (Hero) con ID 1
function getSaleDesign($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM web_design_sale WHERE id = 1 LIMIT 1");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: [];
    } catch (PDOException $e) { return []; }
}

// Carga inicial de datos para mostrar en los campos
$productos_list = getSaleProducts($conn);
$sale_design    = getSaleDesign($conn);

/* =====================================================
   LÓGICA DE GUARDADO (POST)
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // 1. GUARDAR TÍTULO HERO (Individual)
        if (isset($_POST['btn_save_hero_title'])) {
            $title = $_POST['hero_title'] ?? '';
            // Actualiza solo el título en la base de datos
            $conn->prepare("UPDATE web_design_sale SET hero_title = ? WHERE id = 1")->execute([$title]);
            $message = "Title updated.";
        }

        // 2. GUARDAR SUBTÍTULO HERO (Individual)
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $subtitle = $_POST['hero_subtitle'] ?? '';
            // Actualiza solo el subtítulo en la base de datos
            $conn->prepare("UPDATE web_design_sale SET hero_subtitle = ? WHERE id = 1")->execute([$subtitle]);
            $message = "Subtitle updated.";
        }

        // 3. ELIMINAR TEXTOS HERO (Individual)
        elseif (isset($_POST['btn_del_hero_title'])) {
            // Borra el contenido del título
            $conn->prepare("UPDATE web_design_sale SET hero_title = '' WHERE id = 1")->execute();
            $message = "Title deleted.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            // Borra el contenido del subtítulo
            $conn->prepare("UPDATE web_design_sale SET hero_subtitle = '' WHERE id = 1")->execute();
            $message = "Subtitle deleted.";
        }

        // 4. GUARDAR TODO / GLOBAL (Botón Save All)
        elseif (isset($_POST['btn_save_global'])) {
            $title    = $_POST['hero_title'] ?? '';
            $subtitle = $_POST['hero_subtitle'] ?? '';
            
            // Actualiza título y subtítulo al mismo tiempo
            $conn->prepare("UPDATE web_design_sale SET hero_title = ?, hero_subtitle = ? WHERE id = 1")
                 ->execute([$title, $subtitle]);
            
            // Si se subió una nueva imagen, procesarla también
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImageSale($conn, $sale_design);
            }
            $message = "Full configuration saved.";
        }

        // 5. GESTIÓN IMAGEN HERO (Subir y Borrar)
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                // Llama a la función auxiliar para guardar la imagen
                processHeroImageSale($conn, $sale_design);
                $message = "Background image updated.";
            } else {
                $message = "Select a valid image.";
            }
        }
        elseif (isset($_POST['btn_del_hero_img'])) {
            // Borra el archivo físico si existe
            if (!empty($sale_design['hero_image'])) {
                deleteFile(__DIR__ . '/../../' . $sale_design['hero_image']);
            }
            // Pone el campo como NULL en la base de datos
            $conn->prepare("UPDATE web_design_sale SET hero_image = NULL WHERE id = 1")->execute();
            $message = "Background image deleted.";
        }

        // 6. GESTIÓN DE PRODUCTOS (Agregar)
        elseif (isset($_POST['btn_save_product'])) {
            // Recoger datos del formulario
            $name      = $_POST['prod_name'] ?? '';
            $brand     = $_POST['prod_brand'] ?? '';
            $price     = $_POST['prod_price'] ?? 0;
            $old_price = $_POST['prod_old_price'] ?? 0;
            $discount  = $_POST['prod_discount'] ?? '';

            // Procesar imagen del producto
            if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] === 0) {
                $ext = strtolower(pathinfo($_FILES['prod_image']['name'], PATHINFO_EXTENSION));
                $newName = 'sale_prod_' . time() . '.' . $ext;
                $dbPath = 'images/sale/' . $newName;
                
                // Crear carpeta si no existe
                $targetDir = __DIR__ . '/../../images/sale/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

                // Mover archivo y guardar en BD
                if (move_uploaded_file($_FILES['prod_image']['tmp_name'], $targetDir . $newName)) {
                    $sql = "INSERT INTO web_design_sale_products (name, brand, price, old_price, discount, image) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$name, $brand, $price, $old_price, $discount, $dbPath]);
                    $message = "Product added.";
                }
            }
        }
        // 7. ELIMINAR PRODUCTO
        elseif (isset($_POST['btn_del_product'])) {
            $id = $_POST['del_id'];
            // Obtener ruta de la imagen antes de borrar registro
            $stmt = $conn->prepare("SELECT image FROM web_design_sale_products WHERE id = ?");
            $stmt->execute([$id]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod) {
                // Borrar archivo físico y luego la fila de la BD
                deleteFile(__DIR__ . '/../../' . $prod['image']);
                $conn->prepare("DELETE FROM web_design_sale_products WHERE id = ?")->execute([$id]);
                $message = "Product deleted.";
            }
        }

        // Refrescar variables para que la vista se actualice al instante
        $productos_list = getSaleProducts($conn);
        $sale_design    = getSaleDesign($conn);

    } catch (PDOException $e) {
        $message = "System error: " . $e->getMessage();
    }
}

// --- FUNCIONES AUXILIARES ---

// Procesa la subida de imagen Hero y borra la anterior
function processHeroImageSale($conn, $currentData) {
    $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
    $newName = 'hero_sale_' . time() . '.' . $ext;
    $dbPath = 'images/sale/' . $newName;
    
    $targetDir = __DIR__ . '/../../images/sale/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $targetDir . $newName)) {
        // Si ya existía una imagen, la borramos del servidor
        if (!empty($currentData['hero_image'])) {
            deleteFile(__DIR__ . '/../../' . $currentData['hero_image']);
        }
        // Actualizamos la ruta en la base de datos
        $conn->prepare("UPDATE web_design_sale SET hero_image = ? WHERE id = 1")->execute([$dbPath]);
        return true;
    }
    return false;
}

// Borra un archivo del servidor si existe
function deleteFile($path) {
    if (file_exists($path)) {
        @unlink($path);
    }
}
?>