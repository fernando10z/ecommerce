<?php
// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir conexión a la base de datos
require_once __DIR__ . '/../conexion/conexion.php'; 

// ID del usuario logueado
$usuario_actual_id = $_SESSION['user_id'] ?? 1;

$message = "";

// =====================================================
// FUNCIÓN PROFESIONAL DE AUDITORÍA (JSON)
// =====================================================
if (!function_exists('registrarAuditoria')) {
    function registrarAuditoria($conn, $user_id, $entity_type, $entity_id, $action, $old_values, $new_values) {
        $changed_fields = [];
        
        if (is_array($old_values) && is_array($new_values)) {
            foreach ($new_values as $key => $value) {
                if (!array_key_exists($key, $old_values) || $old_values[$key] !== $value) {
                    $changed_fields[] = $key;
                }
            }
        } elseif ($action === 'create' && is_array($new_values)) {
            $changed_fields = array_keys($new_values);
        }

        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $request_id = uniqid('req_'); 

        // 1. Obtenemos la fecha y hora actual en la zona horaria de Lima
        $zona_horaria = new DateTimeZone('America/Lima');
        $fecha_actual = new DateTime('now', $zona_horaria);
        $fecha_lima = $fecha_actual->format('Y-m-d H:i:s');

        try {
            // 2. Cambiamos NOW() por un signo de interrogación (?)
            $sql = "INSERT INTO audit_logs 
                    (organization_id, user_id, entity_type, entity_id, action, old_values, new_values, changed_fields, ip_address, user_agent, request_id, created_at) 
                    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            
            // 3. Añadimos $fecha_lima al final del array de ejecución
            $stmt->execute([
                $user_id, $entity_type, $entity_id, $action,
                !empty($old_values) ? json_encode($old_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($new_values) ? json_encode($new_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($changed_fields) ? json_encode($changed_fields) : null,
                $ip_address, $user_agent, $request_id, 
                $fecha_lima
            ]);
        } catch (PDOException $e) {
            error_log("Error de auditoría: " . $e->getMessage());
        }
    }
}

// --- FUNCIONES DE LECTURA ---

function getSaleProducts($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM web_design_sale_products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) { return []; }
}

function getSaleDesign($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM web_design_sale WHERE id = 1 LIMIT 1");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: [];
    } catch (PDOException $e) { return []; }
}

// Carga inicial de datos (nuestro "Antes")
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
            $conn->prepare("UPDATE web_design_sale SET hero_title = ? WHERE id = 1")->execute([$title]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_sale', 1, 'update', 
                ['hero_title' => $sale_design['hero_title']], ['hero_title' => $title]);
            $message = "Title updated.";
        }

        // 2. GUARDAR SUBTÍTULO HERO (Individual)
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $subtitle = $_POST['hero_subtitle'] ?? '';
            $conn->prepare("UPDATE web_design_sale SET hero_subtitle = ? WHERE id = 1")->execute([$subtitle]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_sale', 1, 'update', 
                ['hero_subtitle' => $sale_design['hero_subtitle']], ['hero_subtitle' => $subtitle]);
            $message = "Subtitle updated.";
        }

        // 3. ELIMINAR TEXTOS HERO (Individual)
        elseif (isset($_POST['btn_del_hero_title'])) {
            $conn->prepare("UPDATE web_design_sale SET hero_title = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_sale', 1, 'delete', 
                ['hero_title' => $sale_design['hero_title']], ['hero_title' => '']);
            $message = "Title deleted.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            $conn->prepare("UPDATE web_design_sale SET hero_subtitle = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_sale', 1, 'delete', 
                ['hero_subtitle' => $sale_design['hero_subtitle']], ['hero_subtitle' => '']);
            $message = "Subtitle deleted.";
        }

        // 4. GUARDAR TODO / GLOBAL (Botón Save All)
        elseif (isset($_POST['btn_save_global'])) {
            $title    = $_POST['hero_title'] ?? '';
            $subtitle = $_POST['hero_subtitle'] ?? '';
            
            $conn->prepare("UPDATE web_design_sale SET hero_title = ?, hero_subtitle = ? WHERE id = 1")
                 ->execute([$title, $subtitle]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_sale', 1, 'update', 
                ['hero_title' => $sale_design['hero_title'], 'hero_subtitle' => $sale_design['hero_subtitle']], 
                ['hero_title' => $title, 'hero_subtitle' => $subtitle]);
            
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImageSale($conn, $sale_design, $usuario_actual_id);
            }
            $message = "Full configuration saved.";
        }

        // 5. GESTIÓN IMAGEN HERO (Subir y Borrar)
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImageSale($conn, $sale_design, $usuario_actual_id);
                $message = "Background image updated.";
            } else {
                $message = "Select a valid image.";
            }
        }
        elseif (isset($_POST['btn_del_hero_img'])) {
            if (!empty($sale_design['hero_image'])) {
                deleteFile(__DIR__ . '/../../' . $sale_design['hero_image']);
            }
            $conn->prepare("UPDATE web_design_sale SET hero_image = NULL WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_sale', 1, 'delete', 
                ['hero_image' => $sale_design['hero_image']], ['hero_image' => null]);
            $message = "Background image deleted.";
        }

        // 6. GESTIÓN DE PRODUCTOS (Agregar)
        elseif (isset($_POST['btn_save_product'])) {
            $name      = $_POST['prod_name'] ?? '';
            $brand     = $_POST['prod_brand'] ?? '';
            $price     = $_POST['prod_price'] ?? 0;
            $old_price = $_POST['prod_old_price'] ?? 0;
            $discount  = $_POST['prod_discount'] ?? '';

            if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] === 0) {
                $ext = strtolower(pathinfo($_FILES['prod_image']['name'], PATHINFO_EXTENSION));
                $newName = 'sale_prod_' . time() . '.' . $ext;
                $dbPath = 'images/sale/' . $newName;
                
                $targetDir = __DIR__ . '/../../images/sale/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

                if (move_uploaded_file($_FILES['prod_image']['tmp_name'], $targetDir . $newName)) {
                    $sql = "INSERT INTO web_design_sale_products (name, brand, price, old_price, discount, image) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$name, $brand, $price, $old_price, $discount, $dbPath]);
                    
                    // AUDITORÍA: Obtenemos el ID del producto recién creado
                    $nuevo_id = $conn->lastInsertId();
                    registrarAuditoria($conn, $usuario_actual_id, 'web_design_sale_products', $nuevo_id, 'create', 
                        [], 
                        ['name' => $name, 'brand' => $brand, 'price' => $price, 'old_price' => $old_price, 'discount' => $discount, 'image' => $dbPath]
                    );
                    
                    $message = "Product added.";
                }
            }
        }
        // 7. ELIMINAR PRODUCTO
        elseif (isset($_POST['btn_del_product'])) {
            $id = $_POST['del_id'];
            // Cambiado a SELECT * para guardar toda la información antes de borrar
            $stmt = $conn->prepare("SELECT * FROM web_design_sale_products WHERE id = ?");
            $stmt->execute([$id]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod) {
                deleteFile(__DIR__ . '/../../' . $prod['image']);
                $conn->prepare("DELETE FROM web_design_sale_products WHERE id = ?")->execute([$id]);
                
                // AUDITORÍA: Guardamos todo el array del producto en old_values
                registrarAuditoria($conn, $usuario_actual_id, 'web_design_sale_products', $id, 'delete', 
                    $prod, 
                    []
                );
                
                $message = "Product deleted.";
            }
        }

        // 8. ACTUALIZAR PRODUCTO (OFERTA)
        elseif (isset($_POST['btn_update_product'])) {
            $id = $_POST['edit_id'];
            $name = $_POST['edit_prod_name'] ?? '';
            $brand = $_POST['edit_prod_brand'] ?? '';
            $price = $_POST['edit_prod_price'] ?? 0;
            $old_price = $_POST['edit_prod_old_price'] ?? 0;
            $discount = $_POST['edit_prod_discount'] ?? '';
            
            // Consultamos el producto actual para auditoría y conservar la imagen si no se sube una nueva
            $stmt = $conn->prepare("SELECT * FROM web_design_sale_products WHERE id = ?");
            $stmt->execute([$id]);
            $prod_actual = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod_actual) {
                $dbPath = $prod_actual['image']; 
                
                // Procesar nueva imagen si el usuario la seleccionó
                if (isset($_FILES['edit_prod_image']) && $_FILES['edit_prod_image']['error'] === 0) {
                    $ext = strtolower(pathinfo($_FILES['edit_prod_image']['name'], PATHINFO_EXTENSION));
                    $newName = 'sale_prod_' . time() . '.' . $ext;
                    $targetDir = __DIR__ . '/../../images/sale/';
                    
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    
                    if (move_uploaded_file($_FILES['edit_prod_image']['tmp_name'], $targetDir . $newName)) {
                        deleteFile(__DIR__ . '/../../' . $dbPath); // Borra la imagen antigua del servidor
                        $dbPath = 'images/sale/' . $newName; // Actualiza la ruta
                    }
                }
                
                // Ejecutar actualización
                $conn->prepare("UPDATE web_design_sale_products SET name = ?, brand = ?, price = ?, old_price = ?, discount = ?, image = ? WHERE id = ?")
                     ->execute([$name, $brand, $price, $old_price, $discount, $dbPath, $id]);
                     
                // Registro de auditoría
                registrarAuditoria($conn, $usuario_actual_id, 'web_design_sale_products', $id, 'update', 
                    $prod_actual, 
                    ['name' => $name, 'brand' => $brand, 'price' => $price, 'old_price' => $old_price, 'discount' => $discount, 'image' => $dbPath]
                );
                
                $message = "Oferta actualizada correctamente.";
            }
        }

        // Refrescar variables
        $productos_list = getSaleProducts($conn);
        $sale_design    = getSaleDesign($conn);

    } catch (PDOException $e) {
        $message = "System error: " . $e->getMessage();
    }
}

// --- FUNCIONES AUXILIARES ---

// Se añadió $user_id a los parámetros para auditar el cambio de imagen
function processHeroImageSale($conn, $currentData, $user_id) {
    $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
    $newName = 'hero_sale_' . time() . '.' . $ext;
    $dbPath = 'images/sale/' . $newName;
    
    $targetDir = __DIR__ . '/../../images/sale/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $targetDir . $newName)) {
        if (!empty($currentData['hero_image'])) {
            deleteFile(__DIR__ . '/../../' . $currentData['hero_image']);
        }
        $conn->prepare("UPDATE web_design_sale SET hero_image = ? WHERE id = 1")->execute([$dbPath]);
        
        // AUDITORÍA
        registrarAuditoria($conn, $user_id, 'web_design_sale', 1, 'update', 
            ['hero_image' => $currentData['hero_image']], 
            ['hero_image' => $dbPath]
        );
        
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