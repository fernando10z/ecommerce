<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// --- READ FUNCTIONS ---

function getMenProducts($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM web_design_men_products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) { return []; }
}

function getMenDesign($conn) {
    try {
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
            $conn->prepare("UPDATE web_design_men SET hero_titulo = ? WHERE id = 1")->execute([$title]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_men', 1, 'update', 
                ['hero_titulo' => $design_data['hero_titulo']], ['hero_titulo' => $title]);
            $message = "Título actualizado.";
        }

        // ---------------------------------------------------
        // 2. INDIVIDUAL SAVE: SUBTITLE
        // ---------------------------------------------------
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $subtitle = $_POST['hero_subtitle'] ?? '';
            $conn->prepare("UPDATE web_design_men SET hero_subtitulo = ? WHERE id = 1")->execute([$subtitle]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_men', 1, 'update', 
                ['hero_subtitulo' => $design_data['hero_subtitulo']], ['hero_subtitulo' => $subtitle]);
            $message = "Subtítulo actualizado.";
        }

        // ---------------------------------------------------
        // 3. INDIVIDUAL DELETE
        // ---------------------------------------------------
        elseif (isset($_POST['btn_del_hero_title'])) {
            $conn->prepare("UPDATE web_design_men SET hero_titulo = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_men', 1, 'delete', 
                ['hero_titulo' => $design_data['hero_titulo']], ['hero_titulo' => '']);
            $message = "Título eliminado.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            $conn->prepare("UPDATE web_design_men SET hero_subtitulo = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_men', 1, 'delete', 
                ['hero_subtitulo' => $design_data['hero_subtitulo']], ['hero_subtitulo' => '']);
            $message = "Subtítulo eliminado.";
        }

        // ---------------------------------------------------
        // 4. SAVE ALL (GLOBAL)
        // ---------------------------------------------------
        elseif (isset($_POST['btn_save_global'])) { 
            $title    = $_POST['hero_title'] ?? '';
            $subtitle = $_POST['hero_subtitle'] ?? '';
            
            $conn->prepare("UPDATE web_design_men SET hero_titulo = ?, hero_subtitulo = ? WHERE id = 1")
                 ->execute([$title, $subtitle]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_men', 1, 'update', 
                ['hero_titulo' => $design_data['hero_titulo'], 'hero_subtitulo' => $design_data['hero_subtitulo']], 
                ['hero_titulo' => $title, 'hero_subtitulo' => $subtitle]);
            
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImage($conn, $design_data, $usuario_actual_id);
            }
            $message = "Configuración completa guardada.";
        }

        // ---------------------------------------------------
        // 5. HERO IMAGE MANAGEMENT
        // ---------------------------------------------------
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImage($conn, $design_data, $usuario_actual_id);
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
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_men', 1, 'delete', 
                ['hero_imagen' => $design_data['hero_imagen']], ['hero_imagen' => null]);
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
                    
                    // AUDITORÍA: Obtenemos el ID del producto recién creado
                    $nuevo_id = $conn->lastInsertId();
                    registrarAuditoria($conn, $usuario_actual_id, 'web_design_men_products', $nuevo_id, 'create', 
                        [], // old_values está vacío porque es nuevo
                        ['nombre' => $name, 'marca' => $brand, 'precio' => $price, 'imagen' => $dbPath]
                    );
                    
                    $message = "Producto agregado.";
                }
            }
        }
        elseif (isset($_POST['btn_del_product'])) {
            $id = $_POST['del_id'];
            // Cambié "SELECT imagen" por "SELECT *" para guardar todos los datos antes de borrarlos
            $stmt = $conn->prepare("SELECT * FROM web_design_men_products WHERE id = ?");
            $stmt->execute([$id]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod) {
                deleteFile(__DIR__ . '/../../' . $prod['imagen']);
                $conn->prepare("DELETE FROM web_design_men_products WHERE id = ?")->execute([$id]);
                
                // AUDITORÍA: Guardamos toda la info del producto en old_values
                registrarAuditoria($conn, $usuario_actual_id, 'web_design_men_products', $id, 'delete', 
                    $prod, // Guardamos el array completo con todo lo que era el producto
                    []     // new_values está vacío porque se eliminó
                );
                
                $message = "Producto eliminado.";
            }
        }

        // ---------------------------------------------------
        // 7. PRODUCT UPDATE
        // ---------------------------------------------------
        elseif (isset($_POST['btn_update_product'])) {
            $id = $_POST['edit_id'];
            $name = $_POST['edit_prod_name'] ?? '';
            $brand = $_POST['edit_prod_brand'] ?? '';
            $price = $_POST['edit_prod_price'] ?? 0;
            
            // Consultamos el producto actual para auditoría y conservar la imagen si no se sube una nueva
            $stmt = $conn->prepare("SELECT * FROM web_design_men_products WHERE id = ?");
            $stmt->execute([$id]);
            $prod_actual = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod_actual) {
                $dbPath = $prod_actual['imagen']; 
                
                // Procesar nueva imagen si el usuario la seleccionó
                if (isset($_FILES['edit_prod_image']) && $_FILES['edit_prod_image']['error'] === 0) {
                    $ext = strtolower(pathinfo($_FILES['edit_prod_image']['name'], PATHINFO_EXTENSION));
                    $newName = 'men_prod_' . time() . '.' . $ext;
                    $targetDir = __DIR__ . '/../../images/men/';
                    
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    
                    if (move_uploaded_file($_FILES['edit_prod_image']['tmp_name'], $targetDir . $newName)) {
                        deleteFile(__DIR__ . '/../../' . $dbPath); // Borra la imagen antigua del servidor
                        $dbPath = 'images/men/' . $newName; // Actualiza la ruta en la base de datos
                    }
                }
                
                // Ejecutar actualización
                $conn->prepare("UPDATE web_design_men_products SET nombre = ?, marca = ?, precio = ?, imagen = ? WHERE id = ?")
                     ->execute([$name, $brand, $price, $dbPath, $id]);
                     
                // Registro de auditoría
                registrarAuditoria($conn, $usuario_actual_id, 'web_design_men_products', $id, 'update', 
                    $prod_actual, 
                    ['nombre' => $name, 'marca' => $brand, 'precio' => $price, 'imagen' => $dbPath]
                );
                
                $message = "Producto actualizado correctamente.";
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

// Añadí el $user_id a los parámetros para poder auditar desde aquí adentro
function processHeroImage($conn, $currentData, $user_id) {
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
        
        // AUDITORÍA
        registrarAuditoria($conn, $user_id, 'web_design_men', 1, 'update', 
            ['hero_imagen' => $currentData['hero_imagen']], 
            ['hero_imagen' => $dbPath]
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