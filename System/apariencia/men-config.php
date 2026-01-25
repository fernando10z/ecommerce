<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ajusta la ruta a tu conexión si es necesario
require_once __DIR__ . '/../conexion/conexion.php';

$mensaje = "";

// Función para obtener los productos actuales
function obtenerProductosMen($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM web_design_men ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        return [];
    }
}

// Guardamos la lista en una variable para usarla en el system
$productos_list = obtenerProductosMen($conn);

/* =====================================================
   LÓGICA DE GUARDADO (AGREGAR Y ELIMINAR)
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // --- AGREGAR NUEVO PRODUCTO ---
        if (isset($_POST['btn_save_product'])) {
            $nombre = $_POST['prod_name'] ?? '';
            $marca  = $_POST['prod_brand'] ?? '';
            $precio = $_POST['prod_price'] ?? 0;
            
            // Validación de imagen
            if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] === 0) {
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $extension = strtolower(pathinfo($_FILES['prod_image']['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, $permitidos)) {
                    // Ruta Física: (System/apariencia -> sube 2 niveles -> images/men)
                    $carpeta = __DIR__ . '/../../images/men/';
                    
                    if (!is_dir($carpeta)) {
                        mkdir($carpeta, 0755, true);
                    }
                    
                    $nuevoNombre = 'men_' . time() . '.' . $extension;
                    
                    if (move_uploaded_file($_FILES['prod_image']['tmp_name'], $carpeta . $nuevoNombre)) {
                        // Ruta BD
                        $rutaBD = 'images/men/' . $nuevoNombre;
                        
                        $sql = "INSERT INTO web_design_men (nombre, marca, precio, imagen) VALUES (?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        
                        if ($stmt->execute([$nombre, $marca, $precio, $rutaBD])) {
                            $mensaje = "Producto agregado correctamente.";
                        }
                    }
                } else {
                    $mensaje = "Error: Formato de imagen no permitido.";
                }
            } else {
                $mensaje = "Debes seleccionar una imagen.";
            }
        }

        // --- ELIMINAR PRODUCTO ---
        elseif (isset($_POST['btn_del_product'])) {
            $id = $_POST['del_id'];
            
            // 1. Obtener imagen
            $stmt = $conn->prepare("SELECT imagen FROM web_design_men WHERE id = ?");
            $stmt->execute([$id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($item) {
                $archivoFisico = __DIR__ . '/../../' . $item['imagen'];
                if (file_exists($archivoFisico)) {
                    unlink($archivoFisico);
                }
                
                // 2. Eliminar de BD
                $conn->prepare("DELETE FROM web_design_men WHERE id = ?")->execute([$id]);
                $mensaje = "Producto eliminado.";
            }
        }

        // Refrescar la lista después de la acción
        $productos_list = obtenerProductosMen($conn);

    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>