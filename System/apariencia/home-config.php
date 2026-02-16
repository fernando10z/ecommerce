<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../conexion/conexion.php';

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
                unlink($ruta); // Borrar el archivo real
            }
        }
    }
}

$datos_actuales = obtenerDatosActuales($conn);
$mensaje = "";

/* =====================================================
   LÓGICA DE ACCIONES
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // 0. GUARDADO GENERAL (NUEVO: Actualiza todos los textos a la vez)
        if (isset($_POST['btn_guardar_global'])) {
            $sql = "UPDATE web_design_home SET 
                    hero_label = ?, 
                    hero_title = ?, 
                    hero_subtitle = ?, 
                    cat_label = ?, 
                    cat_title = ?, 
                    news_title = ?, 
                    news_subtitle = ? 
                    WHERE id = 1";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $_POST['hero_label'] ?? '',
                $_POST['hero_title'] ?? '',
                $_POST['hero_subtitle'] ?? '',
                $_POST['cat_label'] ?? '',
                $_POST['cat_title'] ?? '',
                $_POST['news_title'] ?? '',
                $_POST['news_subtitle'] ?? ''
            ]);
            
            $mensaje = "¡Toda la información de texto ha sido actualizada!";
        }

        // 1. ETIQUETA SUPERIOR (LABEL)
        elseif (isset($_POST['btn_save_label'])) {
            $val = $_POST['hero_label'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET hero_label = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Etiqueta actualizada.";
        }
        elseif (isset($_POST['btn_delete_label'])) {
            $conn->query("UPDATE web_design_home SET hero_label = '' WHERE id = 1");
            $mensaje = "Etiqueta eliminada.";
        }

        // 2. TÍTULO PRINCIPAL
        elseif (isset($_POST['btn_save_title'])) {
            $val = $_POST['hero_title'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET hero_title = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Título actualizado.";
        }
        elseif (isset($_POST['btn_delete_title'])) {
            $conn->query("UPDATE web_design_home SET hero_title = '' WHERE id = 1");
            $mensaje = "Título eliminado.";
        }

        // 3. DESCRIPCIÓN
        elseif (isset($_POST['btn_save_desc'])) {
            $val = $_POST['hero_subtitle'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET hero_subtitle = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Descripción actualizada.";
        }
        elseif (isset($_POST['btn_delete_desc'])) {
            $conn->query("UPDATE web_design_home SET hero_subtitle = '' WHERE id = 1");
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
                        $mensaje = "Imagen 'Hombre' actualizada.";
                    }
                }
            }
        }
        elseif (isset($_POST['btn_delete_man'])) {
            $conn->query("UPDATE web_design_home SET image_man = '' WHERE id = 1");
            eliminarImagenFisica($datos_actuales['image_man']);
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
                        $mensaje = "Imagen 'Sale' actualizada.";
                    }
                }
            }
        }
        elseif (isset($_POST['btn_delete_sale'])) {
            $conn->query("UPDATE web_design_home SET image_sale = '' WHERE id = 1");
            eliminarImagenFisica($datos_actuales['image_sale']);
            $mensaje = "Imagen 'Sale' eliminada.";
        }

        // 8. ETIQUETA SUPERIOR (CATEGORÍA)
        elseif (isset($_POST['btn_save_cat_label'])) {
            $val = $_POST['cat_label'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET cat_label = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Etiqueta actualizada.";
        }
        elseif (isset($_POST['btn_delete_cat_label'])) {
            $conn->query("UPDATE web_design_home SET cat_label = '' WHERE id = 1");
            $mensaje = "Etiqueta eliminada.";
        }

        // 9. TÍTULO PRINCIPAL (CATEGORÍA)
        elseif (isset($_POST['btn_save_cat_title'])) {
            $val = $_POST['cat_title'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET cat_title = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Título actualizado.";
        }
        elseif (isset($_POST['btn_delete_cat_title'])) {
            $conn->query("UPDATE web_design_home SET cat_title = '' WHERE id = 1");
            $mensaje = "Título eliminado.";
        }

        // 10. TÍTULO PRINCIPAL (NOTICIAS)
        elseif (isset($_POST['btn_save_news_title'])) {
            $val = $_POST['news_title'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET news_title = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Título actualizado.";
        }
        elseif (isset($_POST['btn_delete_news_title'])) {
            $conn->query("UPDATE web_design_home SET news_title = '' WHERE id = 1");
            $mensaje = "Título eliminado.";
        }

        // 11. DESCRIPCIÓN (NOTICIAS)
        elseif (isset($_POST['btn_save_news_desc'])) {
            $val = $_POST['news_subtitle'] ?? '';
            $stmt = $conn->prepare("UPDATE web_design_home SET news_subtitle = ? WHERE id = 1");
            $stmt->execute([$val]);
            $mensaje = "Descripción actualizada.";
        }
        elseif (isset($_POST['btn_delete_news_desc'])) {
            $conn->query("UPDATE web_design_home SET news_subtitle = '' WHERE id = 1");
            $mensaje = "Descripción eliminada.";
        }

        // Refrescar datos
        $datos_actuales = obtenerDatosActuales($conn);

    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>