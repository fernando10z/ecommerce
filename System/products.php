<?php
// Inicia la sesión de forma segura y carga el archivo de conexión a la base de datos principal
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexion/conexion.php';

$current_user_id = $_SESSION['user_id'] ?? 1;
$organization_id = 1; 
$system_message = "";

// Función encargada de registrar en la base de datos cualquier cambio, creación o eliminación para mantener el historial de auditoría
if (!function_exists('registerAudit')) {
    function registerAudit($conn, $user_id, $entity_type, $entity_id, $action, $old_values, $new_values) {
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
        
        $timezone = new DateTimeZone('America/Lima');
        $current_date = new DateTime('now', $timezone);
        $lima_date = $current_date->format('Y-m-d H:i:s');

        try {
            $sql = "INSERT INTO audit_logs 
                    (organization_id, user_id, entity_type, entity_id, action, old_values, new_values, changed_fields, ip_address, user_agent, request_id, created_at) 
                    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $user_id, $entity_type, $entity_id, $action,
                !empty($old_values) ? json_encode($old_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($new_values) ? json_encode($new_values, JSON_UNESCAPED_UNICODE) : null,
                !empty($changed_fields) ? json_encode($changed_fields) : null,
                $ip_address, $user_agent, $request_id, $lima_date
            ]);
        } catch (PDOException $e) {
            error_log("Audit error: " . $e->getMessage());
        }
    }
}

// Funciones utilitarias para generar identificadores únicos, crear slugs para URLs amigables y eliminar archivos del servidor
function generateUuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function generateSlug($string) {
    $string = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $string;
}

function deletePhysicalImage($path) {
    if (file_exists($path)) {
        @unlink($path);
    }
}

// Bloque principal que intercepta las peticiones POST para procesar el CRUD completo de productos (crear, actualizar, eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['btn_save_product']) || isset($_POST['btn_update_product'])) {
            // Recopila, sanea y establece valores por defecto de todos los campos del formulario antes de operar en la base de datos
            $product_id = $_POST['edit_id'] ?? null;
            $name = $_POST['prod_name'] ?? '';
            $sku = !empty($_POST['prod_sku']) ? $_POST['prod_sku'] : uniqid('SKU-');
            $base_price = $_POST['prod_price'] ?? 0;
            $category_id = $_POST['prod_category'] ?? null;
            $status = $_POST['prod_status'] ?? 'active';
            $stock = $_POST['prod_stock'] ?? 0;
            $low_stock = $_POST['prod_low_stock'] ?? 5; 
            $short_desc = !empty($_POST['prod_short_desc']) ? $_POST['prod_short_desc'] : null;
            $visibility = $_POST['prod_visibility'] ?? 'visible';
            $is_featured = isset($_POST['prod_featured']) ? 1 : 0;
            
            $is_on_sale = isset($_POST['is_on_sale']) ? 1 : 0;
            $sale_price = ($is_on_sale && !empty($_POST['prod_sale_price'])) ? $_POST['prod_sale_price'] : null;
            $sale_start = ($is_on_sale && !empty($_POST['sale_start_at'])) ? $_POST['sale_start_at'] : null;
            $sale_end = ($is_on_sale && !empty($_POST['sale_end_at'])) ? $_POST['sale_end_at'] : null;

            $conn->beginTransaction();

            if (isset($_POST['btn_save_product'])) {
                $uuid = generateUuid();
                $slug = generateSlug($name) . '-' . time(); 
                
                $sql = "INSERT INTO products (organization_id, uuid, sku, name, slug, base_price, sale_price, sale_start_at, sale_end_at, status, visibility, is_featured, stock_quantity, low_stock_threshold, short_description, created_by) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$organization_id, $uuid, $sku, $name, $slug, $base_price, $sale_price, $sale_start, $sale_end, $status, $visibility, $is_featured, $stock, $low_stock, $short_desc, $current_user_id]);
                
                $target_id = $conn->lastInsertId();
                $action_audit = 'create';
                $old_data = [];
            } else {
                $stmt_old = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $stmt_old->execute([$product_id]);
                $old_data = $stmt_old->fetch(PDO::FETCH_ASSOC);

                $sql = "UPDATE products SET name = ?, sku = ?, base_price = ?, sale_price = ?, sale_start_at = ?, sale_end_at = ?, status = ?, visibility = ?, is_featured = ?, stock_quantity = ?, low_stock_threshold = ?, short_description = ?, updated_by = ? WHERE id = ?";
                $conn->prepare($sql)->execute([$name, $sku, $base_price, $sale_price, $sale_start, $sale_end, $status, $visibility, $is_featured, $stock, $low_stock, $short_desc, $current_user_id, $product_id]);
                
                $target_id = $product_id;
                $action_audit = 'update';
            }

            if ($category_id) {
                $conn->prepare("DELETE FROM product_category_map WHERE product_id = ?")->execute([$target_id]);
                $conn->prepare("INSERT INTO product_category_map (product_id, category_id, is_primary) VALUES (?, ?, 1)")
                    ->execute([$target_id, $category_id]);
            }

            // Valida y procesa la subida de una nueva imagen determinando su carpeta de destino según la categoría asignada
            if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] === 0) {
                $folder = 'men'; 
                if ($category_id) {
                    $stmt_cat_name = $conn->prepare("SELECT name FROM product_categories WHERE id = ?");
                    $stmt_cat_name->execute([$category_id]);
                    $cat_name = strtolower(trim($stmt_cat_name->fetchColumn()));
                    if (in_array($cat_name, ['mujer', 'women'])) { $folder = 'women'; }
                }

                $ext = strtolower(pathinfo($_FILES['prod_image']['name'], PATHINFO_EXTENSION));
                $new_name = 'prod_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/../images/' . $folder . '/'; 
                
                if (!is_dir($target_dir)) { mkdir($target_dir, 0755, true); }
                
                if (move_uploaded_file($_FILES['prod_image']['tmp_name'], $target_dir . $new_name)) {
                    $db_image_path = 'images/' . $folder . '/' . $new_name;
                    $stmt_img = $conn->prepare("SELECT url FROM product_images WHERE product_id = ? AND is_primary = 1");
                    $stmt_img->execute([$target_id]);
                    $old_img = $stmt_img->fetchColumn();
                    if ($old_img) { deletePhysicalImage(__DIR__ . '/' . $old_img); }

                    $conn->prepare("DELETE FROM product_images WHERE product_id = ? AND is_primary = 1")->execute([$target_id]);
                    $conn->prepare("INSERT INTO product_images (product_id, url, is_primary) VALUES (?, ?, 1)")->execute([$target_id, $db_image_path]);
                }
            }

            $conn->commit();
            registerAudit($conn, $current_user_id, 'products', $target_id, $action_audit, $old_data, ['name' => $name, 'sku' => $sku]);
            $system_message = "Producto procesado correctamente.";
        }
        
        elseif (isset($_POST['btn_delete_product'])) {
            $product_id = $_POST['del_id'];
            
            // Eliminación Lógica: Cambiamos is_active a 0 en lugar de hacer un DELETE
            $sql = "UPDATE products SET is_active = 0, updated_by = ? WHERE id = ?";
            $conn->prepare($sql)->execute([$current_user_id, $product_id]);
            
            // Registramos la acción en tu auditoría
            registerAudit($conn, $current_user_id, 'products', $product_id, 'soft_delete', ['is_active' => 1], ['is_active' => 0]);
            
            $system_message = "Producto retirado del Frontend correctamente (mantenido en base de datos).";
        }
    } catch (PDOException $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        $system_message = "System error: " . $e->getMessage();
    }
}

// Consultas a la base de datos para cargar el inventario consolidado y el listado de categorías disponibles
function getAllProductsUnified($conn, $org_id) {
    try {
        $sql = "SELECT p.*, MAX(pi.url) AS primary_image, GROUP_CONCAT(c.name SEPARATOR ', ') AS categories, MAX(pcm.category_id) AS category_id
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
                LEFT JOIN product_category_map pcm ON p.id = pcm.product_id
                LEFT JOIN product_categories c ON pcm.category_id = c.id
                WHERE p.organization_id = ?
                GROUP BY p.id
                ORDER BY p.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$org_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) { 
        error_log("Error al obtener productos: " . $e->getMessage());
        return []; 
    }
}

function getCategoriesList($conn, $org_id) {
    try {
        $stmt = $conn->prepare("SELECT id, name FROM product_categories WHERE organization_id = ? AND is_active = 1 ORDER BY name ASC");
        $stmt->execute([$org_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) { return []; }
}

$categories_list = getCategoriesList($conn, $organization_id);
$products_list = getAllProductsUnified($conn, $organization_id);

// Procesamos los atributos de filtrado ANTES de llegar al HTML
foreach ($products_list as &$product) {
    $product['data_name'] = strtolower(htmlspecialchars($product['name']));
    $product['data_sku'] = strtolower(htmlspecialchars($product['sku']));
    $product['data_category'] = strtolower(htmlspecialchars($product['categories'] ?: 'uncategorized'));
    
    $product['data_status'] = 'activo';
    if (isset($product['is_active']) && $product['is_active'] == '0') {
        $product['data_status'] = 'eliminado';
    } elseif ($product['status'] !== 'active') {
        $product['data_status'] = 'inactivo';
    }
}
unset($product); // Rompemos la referencia por seguridad

$stmt = $conn->prepare("SELECT * FROM organizations LIMIT 1");
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

$defaults = [
    'primary_color' => '#10b981',
    'secondary_color' => '#059669',
    'tertiary_color' => '#ffffff', 
    'logo_name' => 'CRM Pro'
];

if (!$org) {
    $org = array_merge(['name' => 'CRM Pro', 'logo_url' => 'assets/images/collab.png'], $defaults);
}

function getStatusBadge($current, $default) {
    if ($current !== $default && !empty($current)) {
        return '<span class="badge badge-active">Personalizado</span>';
    }
    return '<span class="badge badge-company">Por defecto</span>';
}

$usuario = [
    'nombre' => 'Fernando',
    'email' => 'fernando@ejemplo.com',
    'rol' => 'Administrador'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo htmlspecialchars($org['logo_url'] ?? 'assets/images/collab.png'); ?>" type="image/png">
    <title>Global Products | <?php echo htmlspecialchars($org['name']); ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <?php include 'styles/styles_system.php'; ?>
</head>
<body>
    <div class="app-container">
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header class="header">
            <nav class="flex items-center gap-2 text-sm text-gray-600">
                <span>Home</span> / <span class="text-gray-900 font-medium">Inventory</span>
            </nav>
        </header>

        <div class="p-6">
            <?php if (!empty($system_message)): ?>
                <div class="alert-floating">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <div><?php echo htmlspecialchars($system_message); ?></div>
                </div>
            <?php endif; ?>

            <div class="flex justify-between items-center mb-6 mt-4">
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <i class="fas fa-box-open text-emerald-600"></i> Inventario
                </h1>
                
                <div class="flex gap-3">
                    <button type="button" onclick="confirmarGeneracionReporte()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-900 transition flex items-center gap-2">
                        <i class="fas fa-file-invoice"></i> Generar Reporte
                    </button>
                    
                    <button onclick="openProductModal(true)" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </button>
                </div>
            </div>
            
            <?php include 'includes/products_filters.php'; ?>

            <div class="card">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Detalles del Producto</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Precio base</th>
                                <th>Oferta</th>
                                <th>Estado</th>
                                <th>Visibilidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products_list as $product): ?>
                                <tr class="product-row" 
                                    data-name="<?php echo $product['data_name']; ?>" 
                                    data-sku="<?php echo $product['data_sku']; ?>" 
                                    data-category="<?php echo $product['data_category']; ?>" 
                                    data-status="<?php echo $product['data_status']; ?>">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <img src="<?php echo $product['primary_image'] ? '../' . $product['primary_image'] : 'assets/images/no-image.png'; ?>" class="w-10 h-10 rounded-md object-cover border border-gray-200">
                                            <div>
                                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                                <div class="text-xs text-gray-500">SKU: <?php echo htmlspecialchars($product['sku']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="bg-gray-100 px-2.5 py-1 rounded-full text-xs"><?php echo $product['categories'] ?: 'Uncategorized'; ?></span></td>
                                    <td class="font-medium">
                                        <?php 
                                            // Calcula dinámicamente el estado y color de la barra de progreso dependiendo del inventario actual frente al límite crítico
                                            $actual = (int)$product['stock_quantity'];
                                            $minimo = (int)$product['low_stock_threshold'];
                                            
                                            if ($actual <= $minimo) {
                                                $claseBarra = 'bg-red-500';
                                                $textoEstado = 'Crítico';
                                                $claseTexto = 'text-red-600';
                                            } elseif ($actual <= ($minimo + 10)) {
                                                $claseBarra = 'bg-yellow-400';
                                                $textoEstado = 'Por agotar';
                                                $claseTexto = 'text-yellow-600';
                                            } else {
                                                $claseBarra = 'bg-emerald-500';
                                                $textoEstado = 'Disponible';
                                                $claseTexto = 'text-emerald-600';
                                            }
                                            $porcentaje = ($minimo > 0) ? min(($actual / ($minimo * 2)) * 100, 100) : 100;
                                        ?>
                                        <div class="flex flex-col gap-1 w-28">
                                            <div class="flex justify-between text-[10px] font-bold text-gray-500">
                                                <span>Stock: <?php echo $actual; ?></span>
                                                <span>Min: <?php echo $minimo; ?></span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="<?php echo $claseBarra; ?> h-1.5 rounded-full transition-all" style="width: <?php echo $porcentaje; ?>%"></div>
                                            </div>
                                            <span class="text-[10px] font-bold uppercase <?php echo $claseTexto; ?>"><?php echo $textoEstado; ?></span>
                                        </div>
                                    </td>
                                    <td>$<?php echo number_format($product['base_price'], 2); ?></td>
                                    <td class="text-emerald-600 font-bold"><?php echo $product['sale_price'] ? '$'.number_format($product['sale_price'], 2) : '-'; ?></td>
                                    <td>
                                        <?php if (isset($product['is_active']) && $product['is_active'] == '0'): ?>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                <i class="fas fa-eye-slash mr-1"></i> Eliminado (Front)
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $product['status']==='active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'; ?>">
                                                <?php echo $product['status'] === 'active' ? 'Activo' : ucfirst($product['status']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $visibilidad = $product['visibility'] ?? 'visible';
                                            if ($visibilidad === 'visible'): ?>
                                                <span class="text-blue-600 text-xs font-semibold flex items-center gap-1">
                                                    <i class="fas fa-eye"></i> Visible
                                                </span>
                                            <?php elseif ($visibilidad === 'catalog'): ?>
                                                <span class="text-purple-600 text-xs font-semibold flex items-center gap-1">
                                                    <i class="fas fa-book-open"></i> Catálogo
                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-500 text-xs font-semibold flex items-center gap-1">
                                                    <i class="fas fa-eye-slash"></i> Oculto
                                                </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)" class="p-2 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition"><i class="fas fa-pen"></i></button>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="del_id" value="<?php echo $product['id']; ?>">
                                                <button type="button" name="btn_delete_product" title="Desea borrar este producto para el público" onclick="confirmarAccion(event, this)" class="p-2 bg-red-50 text-red-600 rounded hover:bg-red-100 transition"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include 'modals/products/modal_products.php'; ?>
    </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="javascript/products.js"></script>
</body>
</html>