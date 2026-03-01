<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ajusta la ruta a tu conexión si es necesario
require_once __DIR__ . '/../conexion/conexion.php';

// ID del usuario logueado
$usuario_actual_id = $_SESSION['user_id'] ?? 1;

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
        }

        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $request_id = uniqid('req_'); 

        try {
            $sql = "INSERT INTO audit_logs 
                    (organization_id, user_id, entity_type, entity_id, action, old_values, new_values, changed_fields, ip_address, user_agent, request_id, created_at) 
                    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $user_id, $entity_type, $entity_id, $action,
                !empty($old_values) ? json_encode($old_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($new_values) ? json_encode($new_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($changed_fields) ? json_encode($changed_fields) : null,
                $ip_address, $user_agent, $request_id
            ]);
        } catch (PDOException $e) {
            error_log("Error de auditoría: " . $e->getMessage());
        }
    }
}

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

// Función auxiliar para borrar el archivo físico
if (!function_exists('eliminarImagenFisica')) {
    function eliminarImagenFisica($nombreArchivo) {
        if (!empty($nombreArchivo)) {
            $ruta = __DIR__ . '/../../images/new_collection/' . $nombreArchivo;
            if (file_exists($ruta)) {
                unlink($ruta); 
            }
        }
    }
}

// Cargamos los datos ANTES de cualquier cambio (nuestro "Antes")
$nc_datos = obtenerDatosNewColl($conn);
$mensaje = "";

/* =====================================================
   LÓGICA DE GUARDADO
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // 0. GUARDADO GENERAL
        if (isset($_POST['btn_guardar_global'])) {
            $sql = "UPDATE web_design_new_collection SET 
                    hero_label = ?, hero_title = ?, hero_subtitle = ?, prod_label = ?, prod_title = ? 
                    WHERE id = 1";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $_POST['hero_label'] ?? '', $_POST['hero_title'] ?? '', $_POST['hero_subtitle'] ?? '',
                $_POST['prod_label'] ?? '', $_POST['prod_title'] ?? ''
            ]);
            
            // AUDITORÍA
            $nuevos_datos = [
                'hero_label' => $_POST['hero_label'] ?? '', 'hero_title' => $_POST['hero_title'] ?? '',
                'hero_subtitle' => $_POST['hero_subtitle'] ?? '', 'prod_label' => $_POST['prod_label'] ?? '',
                'prod_title' => $_POST['prod_title'] ?? ''
            ];
            $viejos_datos = array_intersect_key($nc_datos, $nuevos_datos);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'update', $viejos_datos, $nuevos_datos);
            
            $mensaje = "¡Toda la configuración de texto ha sido actualizada!";
        }

        // --- SECCIÓN HERO ---

        // 1. Label Superior
        elseif (isset($_POST['btn_save_hero_label'])) {
            $val = $_POST['hero_label'] ?? '';
            $conn->prepare("UPDATE web_design_new_collection SET hero_label = ? WHERE id = 1")->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'update', 
                ['hero_label' => $nc_datos['hero_label']], ['hero_label' => $val]);
            $mensaje = "Etiqueta Hero actualizada.";
        }
        elseif (isset($_POST['btn_del_hero_label'])) {
            $conn->query("UPDATE web_design_new_collection SET hero_label = '' WHERE id = 1");
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'delete', 
                ['hero_label' => $nc_datos['hero_label']], ['hero_label' => '']);
            $mensaje = "Etiqueta Hero eliminada.";
        }

        // 2. Título Principal
        elseif (isset($_POST['btn_save_hero_title'])) {
            $val = $_POST['hero_title'] ?? '';
            $conn->prepare("UPDATE web_design_new_collection SET hero_title = ? WHERE id = 1")->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'update', 
                ['hero_title' => $nc_datos['hero_title']], ['hero_title' => $val]);
            $mensaje = "Título Hero actualizado.";
        }
        elseif (isset($_POST['btn_del_hero_title'])) {
            $conn->query("UPDATE web_design_new_collection SET hero_title = '' WHERE id = 1");
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'delete', 
                ['hero_title' => $nc_datos['hero_title']], ['hero_title' => '']);
            $mensaje = "Título Hero eliminado.";
        }

        // 3. Subtítulo
        elseif (isset($_POST['btn_save_hero_sub'])) {
            $val = $_POST['hero_subtitle'] ?? '';
            $conn->prepare("UPDATE web_design_new_collection SET hero_subtitle = ? WHERE id = 1")->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'update', 
                ['hero_subtitle' => $nc_datos['hero_subtitle']], ['hero_subtitle' => $val]);
            $mensaje = "Subtítulo actualizado.";
        }
        elseif (isset($_POST['btn_del_hero_sub'])) {
            $conn->query("UPDATE web_design_new_collection SET hero_subtitle = '' WHERE id = 1");
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'delete', 
                ['hero_subtitle' => $nc_datos['hero_subtitle']], ['hero_subtitle' => '']);
            $mensaje = "Subtítulo eliminado.";
        }

        // 4. Imagen de Fondo Hero
        elseif (isset($_POST['btn_save_hero_img'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $extension = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, $permitidos)) {
                    $carpeta = __DIR__ . '/../../images/new_collection/';
                    if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);
                    
                    $nuevoNombre = 'nc_hero_' . time() . '.' . $extension;
                    
                    if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $carpeta . $nuevoNombre)) {
                        eliminarImagenFisica($nc_datos['hero_image']); 
                        $conn->prepare("UPDATE web_design_new_collection SET hero_image = ? WHERE id = 1")->execute([$nuevoNombre]);
                        
                        registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'update', 
                            ['hero_image' => $nc_datos['hero_image']], ['hero_image' => $nuevoNombre]);
                        $mensaje = "Imagen de fondo actualizada.";
                    }
                } else {
                    $mensaje = "Error: Formato no permitido (solo jpg, png, webp).";
                }
            }
        }
        elseif (isset($_POST['btn_del_hero_img'])) {
            $conn->query("UPDATE web_design_new_collection SET hero_image = '' WHERE id = 1");
            eliminarImagenFisica($nc_datos['hero_image']); 
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'delete', 
                ['hero_image' => $nc_datos['hero_image']], ['hero_image' => '']);
            $mensaje = "Imagen de fondo eliminada.";
        }

        // --- SECCIÓN PRODUCTOS (GRID) ---

        // 5. Label Sección Productos
        elseif (isset($_POST['btn_save_prod_label'])) {
            $val = $_POST['prod_label'] ?? '';
            $conn->prepare("UPDATE web_design_new_collection SET prod_label = ? WHERE id = 1")->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'update', 
                ['prod_label' => $nc_datos['prod_label']], ['prod_label' => $val]);
            $mensaje = "Etiqueta de productos actualizada.";
        }
        elseif (isset($_POST['btn_del_prod_label'])) {
            $conn->query("UPDATE web_design_new_collection SET prod_label = '' WHERE id = 1");
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'delete', 
                ['prod_label' => $nc_datos['prod_label']], ['prod_label' => '']);
            $mensaje = "Etiqueta de productos eliminada.";
        }

        // 6. Título Sección Productos
        elseif (isset($_POST['btn_save_prod_title'])) {
            $val = $_POST['prod_title'] ?? '';
            $conn->prepare("UPDATE web_design_new_collection SET prod_title = ? WHERE id = 1")->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'update', 
                ['prod_title' => $nc_datos['prod_title']], ['prod_title' => $val]);
            $mensaje = "Título de productos actualizado.";
        }
        elseif (isset($_POST['btn_del_prod_title'])) {
            $conn->query("UPDATE web_design_new_collection SET prod_title = '' WHERE id = 1");
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_new_collection', 1, 'delete', 
                ['prod_title' => $nc_datos['prod_title']], ['prod_title' => '']);
            $mensaje = "Título de productos eliminado.";
        }

        // Refrescar variables para que el HTML muestre lo más nuevo
        $nc_datos = obtenerDatosNewColl($conn);

    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>