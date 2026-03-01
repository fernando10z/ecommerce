<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../conexion/conexion.php'; 

// ID del usuario logueado
$usuario_actual_id = $_SESSION['user_id'] ?? 1;

$mensaje = "";

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

// Carga Inicial de Datos (Nuestro "Antes")
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
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_women', 1, 'update', 
                ['hero_titulo' => $design_data['hero_titulo']], ['hero_titulo' => $title]);
            $mensaje = "Título actualizado.";
        }

        // 2. GUARDADO INDIVIDUAL: SUBTÍTULO
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $subtitle = $_POST['hero_subtitle'] ?? '';
            $conn->prepare("UPDATE web_design_women SET hero_subtitulo = ? WHERE id = 1")->execute([$subtitle]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_women', 1, 'update', 
                ['hero_subtitulo' => $design_data['hero_subtitulo']], ['hero_subtitulo' => $subtitle]);
            $mensaje = "Descripción actualizada.";
        }

        // 3. ELIMINACIÓN INDIVIDUAL
        elseif (isset($_POST['btn_del_hero_title'])) {
            $conn->prepare("UPDATE web_design_women SET hero_titulo = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_women', 1, 'delete', 
                ['hero_titulo' => $design_data['hero_titulo']], ['hero_titulo' => '']);
            $mensaje = "Título eliminado.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            $conn->prepare("UPDATE web_design_women SET hero_subtitulo = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_women', 1, 'delete', 
                ['hero_subtitulo' => $design_data['hero_subtitulo']], ['hero_subtitulo' => '']);
            $mensaje = "Descripción eliminada.";
        }

        // 4. GUARDAR TODO (GLOBAL)
        elseif (isset($_POST['btn_guardar_global'])) { 
            $title    = $_POST['hero_title'] ?? '';
            $subtitle = $_POST['hero_subtitle'] ?? '';
            
            // 1. Actualizar textos
            $conn->prepare("UPDATE web_design_women SET hero_titulo = ?, hero_subtitulo = ? WHERE id = 1")
                 ->execute([$title, $subtitle]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_women', 1, 'update', 
                ['hero_titulo' => $design_data['hero_titulo'], 'hero_subtitulo' => $design_data['hero_subtitulo']], 
                ['hero_titulo' => $title, 'hero_subtitulo' => $subtitle]);
            
            // 2. Procesar imagen si se sube una nueva
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImageWomen($conn, $design_data, $usuario_actual_id);
            }
            $mensaje = "Configuración completa guardada.";
        }

        // 5. GESTIÓN DE IMAGEN HERO
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImageWomen($conn, $design_data, $usuario_actual_id);
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
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_women', 1, 'delete', 
                ['hero_imagen' => $design_data['hero_imagen']], ['hero_imagen' => null]);
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
                        
                        // AUDITORÍA: Obtenemos el ID del producto creado
                        $nuevo_id = $conn->lastInsertId();
                        registrarAuditoria($conn, $usuario_actual_id, 'web_design_women_products', $nuevo_id, 'create', 
                            [], 
                            ['nombre' => $name, 'marca' => $brand, 'precio' => $price, 'imagen' => $dbPath]
                        );
                        
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
            // Cambiado a SELECT * para auditar todos los datos del producto
            $stmt = $conn->prepare("SELECT * FROM web_design_women_products WHERE id = ?");
            $stmt->execute([$id]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod) {
                deleteFileWomen(__DIR__ . '/../../' . $prod['imagen']);
                $conn->prepare("DELETE FROM web_design_women_products WHERE id = ?")->execute([$id]);
                
                // AUDITORÍA: Guardamos la información del producto borrado
                registrarAuditoria($conn, $usuario_actual_id, 'web_design_women_products', $id, 'delete', 
                    $prod, 
                    []
                );
                
                $mensaje = "Producto eliminado.";
            }
        }

        // 7. ACTUALIZAR PRODUCTO
        elseif (isset($_POST['btn_update_product'])) {
            $id = $_POST['edit_id'];
            $name = $_POST['edit_prod_name'] ?? '';
            $brand = $_POST['edit_prod_brand'] ?? '';
            $price = $_POST['edit_prod_price'] ?? 0;
            
            // Consultamos el producto actual para auditoría y por si no se sube nueva imagen
            $stmt = $conn->prepare("SELECT * FROM web_design_women_products WHERE id = ?");
            $stmt->execute([$id]);
            $prod_actual = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod_actual) {
                $dbPath = $prod_actual['imagen']; 
                
                // Procesar nueva imagen si el usuario la seleccionó
                if (isset($_FILES['edit_prod_image']) && $_FILES['edit_prod_image']['error'] === 0) {
                    $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                    $ext = strtolower(pathinfo($_FILES['edit_prod_image']['name'], PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $permitidos)) {
                        $newName = 'women_prod_' . time() . '.' . $ext;
                        $targetDir = __DIR__ . '/../../images/women/';
                        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                        
                        if (move_uploaded_file($_FILES['edit_prod_image']['tmp_name'], $targetDir . $newName)) {
                            deleteFileWomen(__DIR__ . '/../../' . $dbPath); // Borra la imagen antigua
                            $dbPath = 'images/women/' . $newName; // Actualiza la ruta
                        }
                    } else {
                        $mensaje = "Error: Formato de imagen no permitido.";
                    }
                }
                
                // Ejecutar actualización
                $conn->prepare("UPDATE web_design_women_products SET nombre = ?, marca = ?, precio = ?, imagen = ? WHERE id = ?")
                     ->execute([$name, $brand, $price, $dbPath, $id]);
                
                // Registro de auditoría
                registrarAuditoria($conn, $usuario_actual_id, 'web_design_women_products', $id, 'update', 
                    $prod_actual, 
                    ['nombre' => $name, 'marca' => $brand, 'precio' => $price, 'imagen' => $dbPath]
                );
                
                $mensaje = "Producto actualizado.";
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

// Se añadió $user_id a los parámetros
function processHeroImageWomen($conn, $currentData, $user_id) {
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
        
        // AUDITORÍA
        registrarAuditoria($conn, $user_id, 'web_design_women', 1, 'update', 
            ['hero_imagen' => $currentData['hero_imagen']], 
            ['hero_imagen' => $dbPath]
        );
        
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