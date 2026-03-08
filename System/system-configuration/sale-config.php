<?php
// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir conexión a la base de datos
require_once __DIR__ . '/../conexion/conexion.php'; 

// ID del usuario logueado
$usuario_actual_id = $_SESSION['user_id'] ?? 1;
$organization_id = $_SESSION['organization_id'] ?? 1;

$message = "";

// =====================================================
// FUNCIÓN PROFESIONAL DE AUDITORÍA (JSON)
// =====================================================
if (!function_exists('registrarAuditoria')) {
    function registrarAuditoria($conn, $organization_id, $user_id, $entity_type, $entity_id, $action, $old_values, $new_values) {
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
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            
            // 3. Añadimos $fecha_lima al final del array de ejecución
            $stmt->execute([
                $organization_id, $user_id, $entity_type, $entity_id, $action,
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
        $sql = "SELECT p.id, p.name, p.base_price as old_price, p.sale_price as price, pi.image_path as image 
                FROM products p
                INNER JOIN product_categories pc ON p.id = pc.product_id
                INNER JOIN categories c ON pc.category_id = c.id
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
                WHERE c.slug = 'sale' AND p.status = 'active'
                ORDER BY p.created_at DESC";
        $stmt = $conn->query($sql);
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
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_sale', 1, 'update', 
                ['hero_title' => $sale_design['hero_title']], ['hero_title' => $title]);
            $message = "Title updated.";
        }

        // 2. GUARDAR SUBTÍTULO HERO (Individual)
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $subtitle = $_POST['hero_subtitle'] ?? '';
            $conn->prepare("UPDATE web_design_sale SET hero_subtitle = ? WHERE id = 1")->execute([$subtitle]);
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_sale', 1, 'update', 
                ['hero_subtitle' => $sale_design['hero_subtitle']], ['hero_subtitle' => $subtitle]);
            $message = "Subtitle updated.";
        }

        // 3. ELIMINAR TEXTOS HERO (Individual)
        elseif (isset($_POST['btn_del_hero_title'])) {
            $conn->prepare("UPDATE web_design_sale SET hero_title = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_sale', 1, 'delete', 
                ['hero_title' => $sale_design['hero_title']], ['hero_title' => '']);
            $message = "Title deleted.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            $conn->prepare("UPDATE web_design_sale SET hero_subtitle = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_sale', 1, 'delete', 
                ['hero_subtitle' => $sale_design['hero_subtitle']], ['hero_subtitle' => '']);
            $message = "Subtitle deleted.";
        }

        // 4. GUARDAR TODO / GLOBAL (Botón Save All)
        elseif (isset($_POST['btn_save_global'])) {
            $title    = $_POST['hero_title'] ?? '';
            $subtitle = $_POST['hero_subtitle'] ?? '';
            
            $conn->prepare("UPDATE web_design_sale SET hero_title = ?, hero_subtitle = ? WHERE id = 1")
                 ->execute([$title, $subtitle]);
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_sale', 1, 'update', 
                ['hero_title' => $sale_design['hero_title'], 'hero_subtitle' => $sale_design['hero_subtitle']], 
                ['hero_title' => $title, 'hero_subtitle' => $subtitle]);
            
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImageSale($conn, $sale_design, $usuario_actual_id, $organization_id);
            }
            $message = "Full configuration saved.";
        }

        // 5. GESTIÓN IMAGEN HERO (Subir y Borrar)
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImageSale($conn, $sale_design, $usuario_actual_id, $organization_id);
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
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_sale', 1, 'delete', 
                ['hero_image' => $sale_design['hero_image']], ['hero_image' => null]);
            $message = "Background image deleted.";
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
function processHeroImageSale($conn, $currentData, $user_id, $organization_id) {
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
        registrarAuditoria($conn, $organization_id, $user_id, 'web_design_sale', 1, 'update', 
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