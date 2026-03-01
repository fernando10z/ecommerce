<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../conexion/conexion.php';

// IMPORTANTE: Aquí debes poner la variable de sesión donde guardas el ID del usuario que inició sesión.
// Lo pongo en 1 por defecto para que no te dé error mientras haces pruebas.
$usuario_actual_id = $_SESSION['user_id'] ?? 1; 

// Función para obtener datos actuales
if (!function_exists('obtenerDatosActuales')) {
    function obtenerDatosActuales($conn) {
        try {
            $stmt = $conn->query("SELECT * FROM web_design_home WHERE id = 1");
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
            $ruta = __DIR__ . '/../../images/home/' . $nombreArchivo;
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }
    }
}

// =====================================================
// NUEVA FUNCIÓN PROFESIONAL DE AUDITORÍA
// =====================================================
if (!function_exists('registrarAuditoria')) {
    function registrarAuditoria($conn, $user_id, $entity_type, $entity_id, $action, $old_values, $new_values) {
        $changed_fields = [];
        
        // Comparamos automáticamente qué campos cambiaron
        if (is_array($old_values) && is_array($new_values)) {
            foreach ($new_values as $key => $value) {
                if (!array_key_exists($key, $old_values) || $old_values[$key] !== $value) {
                    $changed_fields[] = $key;
                }
            }
        }

        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $request_id = uniqid('req_'); // Crea un ID único para este movimiento

        try {
            $sql = "INSERT INTO audit_logs 
                    (organization_id, user_id, entity_type, entity_id, action, old_values, new_values, changed_fields, ip_address, user_agent, request_id, created_at) 
                    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $user_id,
                $entity_type,
                $entity_id,
                $action,
                !empty($old_values) ? json_encode($old_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($new_values) ? json_encode($new_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($changed_fields) ? json_encode($changed_fields) : null,
                $ip_address,
                $user_agent,
                $request_id
            ]);
        } catch (PDOException $e) {
            // Si la auditoría falla, guardamos el error en el servidor pero no rompemos la página
            error_log("Error de auditoría: " . $e->getMessage());
        }
    }
}

// Obtenemos los datos ANTES de cualquier cambio (nuestro "old_values")
$datos_actuales = obtenerDatosActuales($conn);
$mensaje = "";

/* =====================================================
   LÓGICA DE ACCIONES
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // 0. GUARDADO GENERAL
        if (isset($_POST['btn_guardar_global'])) {
            $sql = "UPDATE web_design_home SET 
                    hero_label = ?, hero_title = ?, hero_subtitle = ?, 
                    cat_label = ?, cat_title = ?, news_title = ?, news_subtitle = ? 
                    WHERE id = 1";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $_POST['hero_label'] ?? '', $_POST['hero_title'] ?? '', $_POST['hero_subtitle'] ?? '',
                $_POST['cat_label'] ?? '', $_POST['cat_title'] ?? '', $_POST['news_title'] ?? '', $_POST['news_subtitle'] ?? ''
            ]);
            
            // AUDITORÍA: Guardado global
            $nuevos_datos = [
                'hero_label' => $_POST['hero_label'] ?? '', 'hero_title' => $_POST['hero_title'] ?? '',
                'hero_subtitle' => $_POST['hero_subtitle'] ?? '', 'cat_label' => $_POST['cat_label'] ?? '',
                'cat_title' => $_POST['cat_title'] ?? '', 'news_title' => $_POST['news_title'] ?? '',
                'news_subtitle' => $_POST['news_subtitle'] ?? ''
            ];
            $viejos_datos = array_intersect_key($datos_actuales, $nuevos_datos);
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', $viejos_datos, $nuevos_datos);
            
            $mensaje = "¡Toda la información de texto ha sido actualizada!";
        }

        // 1. ETIQUETA SUPERIOR (LABEL)
        elseif (isset($_POST['btn_save_label'])) {
            $val = $_POST['hero_label'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET hero_label = ? WHERE id = 1");
            $stmt->execute([$val]);
            
            // AUDITORÍA
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                ['hero_label' => $datos_actuales['hero_label']], ['hero_label' => $val]);
                
            $mensaje = "Etiqueta actualizada.";
        }
        elseif (isset($_POST['btn_delete_label'])) {
            $conn->query("UPDATE web_design_home SET hero_label = '' WHERE id = 1");
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['hero_label' => $datos_actuales['hero_label']], ['hero_label' => '']);
            $mensaje = "Etiqueta eliminada.";
        }

        // 2. TÍTULO PRINCIPAL
        elseif (isset($_POST['btn_save_title'])) {
            $val = $_POST['hero_title'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET hero_title = ? WHERE id = 1");
            $stmt->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                ['hero_title' => $datos_actuales['hero_title']], ['hero_title' => $val]);
            $mensaje = "Título actualizado.";
        }
        elseif (isset($_POST['btn_delete_title'])) {
            $conn->query("UPDATE web_design_home SET hero_title = '' WHERE id = 1");
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['hero_title' => $datos_actuales['hero_title']], ['hero_title' => '']);
            $mensaje = "Título eliminado.";
        }

        // 3. DESCRIPCIÓN
        elseif (isset($_POST['btn_save_desc'])) {
            $val = $_POST['hero_subtitle'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET hero_subtitle = ? WHERE id = 1");
            $stmt->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                ['hero_subtitle' => $datos_actuales['hero_subtitle']], ['hero_subtitle' => $val]);
            $mensaje = "Descripción actualizada.";
        }
        elseif (isset($_POST['btn_delete_desc'])) {
            $conn->query("UPDATE web_design_home SET hero_subtitle = '' WHERE id = 1");
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['hero_subtitle' => $datos_actuales['hero_subtitle']], ['hero_subtitle' => '']);
            $mensaje = "Descripción eliminada.";
        }

        // 4. IMAGEN DE FONDO
        elseif (isset($_POST['btn_save_image'])) {
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $extension = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, $permitidos)) {
                    $carpeta = __DIR__ . '/../../images/home/';
                    if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);
                    
                    $nuevoNombre = 'hero_' . time() . '.' . $extension;
                    if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $carpeta . $nuevoNombre)) {
                        eliminarImagenFisica($datos_actuales['image_background']); 
                        $stmt = $conn->prepare("UPDATE web_design_home SET image_background = ? WHERE id = 1");
                        $stmt->execute([$nuevoNombre]);
                        
                        // AUDITORÍA
                        registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                            ['image_background' => $datos_actuales['image_background']], ['image_background' => $nuevoNombre]);
                        $mensaje = "Imagen actualizada.";
                    }
                } else {
                    $mensaje = "Error: Formato no permitido.";
                }
            } else {
                $mensaje = "Debes seleccionar una imagen primero.";
            }
        }
        elseif (isset($_POST['btn_delete_image'])) {
            $conn->query("UPDATE web_design_home SET image_background = '' WHERE id = 1");
            eliminarImagenFisica($datos_actuales['image_background']); 
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['image_background' => $datos_actuales['image_background']], ['image_background' => '']);
            $mensaje = "Imagen eliminada.";
        }

        // 5. IMAGEN MUJER
        elseif (isset($_POST['btn_save_woman'])) {
            if (isset($_FILES['image_woman']) && $_FILES['image_woman']['error'] === 0) {
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $extension = strtolower(pathinfo($_FILES['image_woman']['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, $permitidos)) {
                    $carpeta = __DIR__ . '/../../images/home/';
                    if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);
                    
                    $nuevoNombre = 'woman_' . time() . '.' . $extension;
                    if (move_uploaded_file($_FILES['image_woman']['tmp_name'], $carpeta . $nuevoNombre)) {
                        eliminarImagenFisica($datos_actuales['image_woman']);
                        $stmt = $conn->prepare("UPDATE web_design_home SET image_woman = ? WHERE id = 1");
                        $stmt->execute([$nuevoNombre]);
                        
                        registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                            ['image_woman' => $datos_actuales['image_woman']], ['image_woman' => $nuevoNombre]);
                        $mensaje = "Imagen 'Mujer' actualizada.";
                    }
                } else {
                    $mensaje = "Error: Formato no permitido.";
                }
            } else {
                $mensaje = "Debes seleccionar una imagen primero.";
            }
        }
        elseif (isset($_POST['btn_delete_woman'])) {
            $conn->query("UPDATE web_design_home SET image_woman = '' WHERE id = 1");
            eliminarImagenFisica($datos_actuales['image_woman']);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['image_woman' => $datos_actuales['image_woman']], ['image_woman' => '']);
            $mensaje = "Imagen 'Mujer' eliminada.";
        }

        // 6. IMAGEN HOMBRE
        elseif (isset($_POST['btn_save_man'])) {
            if (isset($_FILES['image_man']) && $_FILES['image_man']['error'] === 0) {
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $extension = strtolower(pathinfo($_FILES['image_man']['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, $permitidos)) {
                    $carpeta = __DIR__ . '/../../images/home/';
                    $nuevoNombre = 'man_' . time() . '.' . $extension;
                    if (move_uploaded_file($_FILES['image_man']['tmp_name'], $carpeta . $nuevoNombre)) {
                        eliminarImagenFisica($datos_actuales['image_man']);
                        $stmt = $conn->prepare("UPDATE web_design_home SET image_man = ? WHERE id = 1");
                        $stmt->execute([$nuevoNombre]);
                        
                        registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                            ['image_man' => $datos_actuales['image_man']], ['image_man' => $nuevoNombre]);
                        $mensaje = "Imagen 'Hombre' actualizada.";
                    }
                }
            }
        }
        elseif (isset($_POST['btn_delete_man'])) {
            $conn->query("UPDATE web_design_home SET image_man = '' WHERE id = 1");
            eliminarImagenFisica($datos_actuales['image_man']);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['image_man' => $datos_actuales['image_man']], ['image_man' => '']);
            $mensaje = "Imagen 'Hombre' eliminada.";
        }

        // 7. IMAGEN SALE
        elseif (isset($_POST['btn_save_sale'])) {
            if (isset($_FILES['image_sale']) && $_FILES['image_sale']['error'] === 0) {
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $extension = strtolower(pathinfo($_FILES['image_sale']['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, $permitidos)) {
                    $carpeta = __DIR__ . '/../../images/home/';
                    $nuevoNombre = 'sale_' . time() . '.' . $extension;
                    if (move_uploaded_file($_FILES['image_sale']['tmp_name'], $carpeta . $nuevoNombre)) {
                        eliminarImagenFisica($datos_actuales['image_sale']);
                        $stmt = $conn->prepare("UPDATE web_design_home SET image_sale = ? WHERE id = 1");
                        $stmt->execute([$nuevoNombre]);
                        
                        registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                            ['image_sale' => $datos_actuales['image_sale']], ['image_sale' => $nuevoNombre]);
                        $mensaje = "Imagen 'Sale' actualizada.";
                    }
                }
            }
        }
        elseif (isset($_POST['btn_delete_sale'])) {
            $conn->query("UPDATE web_design_home SET image_sale = '' WHERE id = 1");
            eliminarImagenFisica($datos_actuales['image_sale']);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['image_sale' => $datos_actuales['image_sale']], ['image_sale' => '']);
            $mensaje = "Imagen 'Sale' eliminada.";
        }

        // 8. ETIQUETA SUPERIOR (CATEGORÍA)
        elseif (isset($_POST['btn_save_cat_label'])) {
            $val = $_POST['cat_label'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET cat_label = ? WHERE id = 1");
            $stmt->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                ['cat_label' => $datos_actuales['cat_label']], ['cat_label' => $val]);
            $mensaje = "Etiqueta actualizada.";
        }
        elseif (isset($_POST['btn_delete_cat_label'])) {
            $conn->query("UPDATE web_design_home SET cat_label = '' WHERE id = 1");
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['cat_label' => $datos_actuales['cat_label']], ['cat_label' => '']);
            $mensaje = "Etiqueta eliminada.";
        }

        // 9. TÍTULO PRINCIPAL (CATEGORÍA)
        elseif (isset($_POST['btn_save_cat_title'])) {
            $val = $_POST['cat_title'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET cat_title = ? WHERE id = 1");
            $stmt->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                ['cat_title' => $datos_actuales['cat_title']], ['cat_title' => $val]);
            $mensaje = "Título actualizado.";
        }
        elseif (isset($_POST['btn_delete_cat_title'])) {
            $conn->query("UPDATE web_design_home SET cat_title = '' WHERE id = 1");
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['cat_title' => $datos_actuales['cat_title']], ['cat_title' => '']);
            $mensaje = "Título eliminado.";
        }

        // 10. TÍTULO PRINCIPAL (NOTICIAS)
        elseif (isset($_POST['btn_save_news_title'])) {
            $val = $_POST['news_title'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET news_title = ? WHERE id = 1");
            $stmt->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                ['news_title' => $datos_actuales['news_title']], ['news_title' => $val]);
            $mensaje = "Título actualizado.";
        }
        elseif (isset($_POST['btn_delete_news_title'])) {
            $conn->query("UPDATE web_design_home SET news_title = '' WHERE id = 1");
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['news_title' => $datos_actuales['news_title']], ['news_title' => '']);
            $mensaje = "Título eliminado.";
        }

        // 11. DESCRIPCIÓN (NOTICIAS)
        elseif (isset($_POST['btn_save_news_desc'])) {
            $val = $_POST['news_subtitle'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET news_subtitle = ? WHERE id = 1");
            $stmt->execute([$val]);
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'update', 
                ['news_subtitle' => $datos_actuales['news_subtitle']], ['news_subtitle' => $val]);
            $mensaje = "Descripción actualizada.";
        }
        elseif (isset($_POST['btn_delete_news_desc'])) {
            $conn->query("UPDATE web_design_home SET news_subtitle = '' WHERE id = 1");
            
            registrarAuditoria($conn, $usuario_actual_id, 'web_design_home', 1, 'delete', 
                ['news_subtitle' => $datos_actuales['news_subtitle']], ['news_subtitle' => '']);
            $mensaje = "Descripción eliminada.";
        }

        // Refrescar datos para que la vista se actualice
        $datos_actuales = obtenerDatosActuales($conn);

    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>