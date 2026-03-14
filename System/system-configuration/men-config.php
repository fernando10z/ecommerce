<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
            $sql = "INSERT INTO audit_logs 
                    (organization_id, user_id, entity_type, entity_id, action, old_values, new_values, changed_fields, ip_address, user_agent, request_id, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            
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

// --- READ FUNCTIONS ---

function getMenProducts($conn) {
    try {
        $sql = "SELECT p.id, p.name, p.base_price, p.sale_price, pi.url as image, p.organization_id
                FROM products p
                INNER JOIN product_category_map pcm ON p.id = pcm.product_id
                INNER JOIN product_categories c ON pcm.category_id = c.id
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
                WHERE (c.name = 'Hombre' OR c.name = 'Men') AND p.status = 'active'
                ORDER BY p.created_at DESC";
        $stmt = $conn->query($sql);
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
$productos_list = getMenProducts($conn);
$design_data   = getMenDesign($conn);

/* =====================================================
   SAVE LOGIC (POST)
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. INDIVIDUAL SAVE: TITLE
        if (isset($_POST['btn_save_hero_title'])) {
            $title = $_POST['hero_title'] ?? '';
            $conn->prepare("UPDATE web_design_men SET hero_titulo = ? WHERE id = 1")->execute([$title]);
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_men', 1, 'update', 
                ['hero_titulo' => $design_data['hero_titulo'] ?? ''], ['hero_titulo' => $title]);
            $message = "Título actualizado.";
        }

        // 2. INDIVIDUAL SAVE: SUBTITLE
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $subtitle = $_POST['hero_subtitle'] ?? '';
            $conn->prepare("UPDATE web_design_men SET hero_subtitulo = ? WHERE id = 1")->execute([$subtitle]);
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_men', 1, 'update', 
                ['hero_subtitulo' => $design_data['hero_subtitulo'] ?? ''], ['hero_subtitulo' => $subtitle]);
            $message = "Subtítulo actualizado.";
        }

        // 3. INDIVIDUAL DELETE
        elseif (isset($_POST['btn_del_hero_title'])) {
            $conn->prepare("UPDATE web_design_men SET hero_titulo = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_men', 1, 'delete', 
                ['hero_titulo' => $design_data['hero_titulo'] ?? ''], ['hero_titulo' => '']);
            $message = "Título eliminado.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            $conn->prepare("UPDATE web_design_men SET hero_subtitulo = '' WHERE id = 1")->execute();
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_men', 1, 'delete', 
                ['hero_subtitulo' => $design_data['hero_subtitulo'] ?? ''], ['hero_subtitulo' => '']);
            $message = "Subtítulo eliminado.";
        }

        // 4. SAVE ALL (GLOBAL)
        elseif (isset($_POST['btn_save_global'])) { 
            $title    = $_POST['hero_title'] ?? '';
            $subtitle = $_POST['hero_subtitle'] ?? '';
            
            $conn->prepare("UPDATE web_design_men SET hero_titulo = ?, hero_subtitulo = ? WHERE id = 1")
                 ->execute([$title, $subtitle]);
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_men', 1, 'update', 
                ['hero_titulo' => $design_data['hero_titulo'] ?? '', 'hero_subtitulo' => $design_data['hero_subtitulo'] ?? ''], 
                ['hero_titulo' => $title, 'hero_subtitulo' => $subtitle]);
            
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImage($conn, $design_data, $usuario_actual_id, $organization_id);
            }
            $message = "Configuración completa guardada.";
        }

        // 5. HERO IMAGE MANAGEMENT
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                processHeroImage($conn, $design_data, $usuario_actual_id, $organization_id);
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
            
            registrarAuditoria($conn, $organization_id, $usuario_actual_id, 'web_design_men', 1, 'delete', 
                ['hero_imagen' => $design_data['hero_imagen'] ?? null], ['hero_imagen' => null]);
            $message = "Imagen de fondo eliminada.";
        }

        // --- REFRESH DATA ---
        $productos_list = getMenProducts($conn);
        $design_data   = getMenDesign($conn);

    } catch (PDOException $e) {
        $message = "Error del sistema: " . $e->getMessage();
    }
}

// --- HELPER FUNCTIONS ---

function processHeroImage($conn, $currentData, $user_id, $org_id) {
    $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
    $newName = 'hero_men_' . time() . '.' . $ext;
    $dbPath = 'images/men/' . $newName;
    
    $targetDir = __DIR__ . '/../../images/men/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $targetDir . $newName)) {
        if (!empty($currentData['hero_imagen'])) {
            deleteFile(__DIR__ . '/../../' . $currentData['hero_imagen']);
        }
        $conn->prepare("UPDATE web_design_men SET hero_imagen = ? WHERE id = 1")->execute([$dbPath]);
        
        registrarAuditoria($conn, $org_id, $user_id, 'web_design_men', 1, 'update', 
            ['hero_imagen' => $currentData['hero_imagen'] ?? null], 
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