<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexion/conexion.php';

// Structural Note: System variables initialization
$current_user_id = $_SESSION['user_id'] ?? 1;
$organization_id = 1; 
$system_message = "";

// =====================================================
// STRUCTURAL NOTE: PROFESSIONAL AUDIT LOG (JSON)
// =====================================================
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

// =====================================================
// STRUCTURAL NOTE: HELPER FUNCTIONS
// =====================================================
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

// =====================================================
// STRUCTURAL NOTE: CRUD OPERATIONS
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['btn_save_product']) || isset($_POST['btn_update_product'])) {
            $product_id = $_POST['edit_id'] ?? null;
            $name = $_POST['prod_name'] ?? '';
            $sku = !empty($_POST['prod_sku']) ? $_POST['prod_sku'] : uniqid('SKU-');
            $base_price = $_POST['prod_price'] ?? 0;
            $category_id = $_POST['prod_category'] ?? null;
            $status = $_POST['prod_status'] ?? 'active';
            $stock = $_POST['prod_stock'] ?? 0;
            $short_desc = !empty($_POST['prod_short_desc']) ? $_POST['prod_short_desc'] : null;
            
            // Campos adicionales según la base de datos
            $visibility = $_POST['prod_visibility'] ?? 'visible';
            $is_featured = isset($_POST['prod_featured']) ? 1 : 0;
            
            // Lógica de Oferta (Sale)
            $is_on_sale = isset($_POST['is_on_sale']) ? 1 : 0;
            $sale_price = ($is_on_sale && !empty($_POST['prod_sale_price'])) ? $_POST['prod_sale_price'] : null;
            $sale_start = ($is_on_sale && !empty($_POST['sale_start_at'])) ? $_POST['sale_start_at'] : null;
            $sale_end = ($is_on_sale && !empty($_POST['sale_end_at'])) ? $_POST['sale_end_at'] : null;

            $conn->beginTransaction();

            if (isset($_POST['btn_save_product'])) {
                $uuid = generateUuid();
                $slug = generateSlug($name) . '-' . time(); 
                $sql = "INSERT INTO products (organization_id, uuid, sku, name, slug, base_price, sale_price, sale_start_at, sale_end_at, status, visibility, is_featured, stock_quantity, short_description, created_by) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$organization_id, $uuid, $sku, $name, $slug, $base_price, $sale_price, $sale_start, $sale_end, $status, $visibility, $is_featured, $stock, $short_desc, $current_user_id]);
                $target_id = $conn->lastInsertId();
                $action_audit = 'create';
                $old_data = [];
            } else {
                $stmt_old = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $stmt_old->execute([$product_id]);
                $old_data = $stmt_old->fetch(PDO::FETCH_ASSOC);

                $sql = "UPDATE products SET name = ?, sku = ?, base_price = ?, sale_price = ?, sale_start_at = ?, sale_end_at = ?, status = ?, visibility = ?, is_featured = ?, stock_quantity = ?, short_description = ?, updated_by = ? WHERE id = ?";
                $conn->prepare($sql)->execute([$name, $sku, $base_price, $sale_price, $sale_start, $sale_end, $status, $visibility, $is_featured, $stock, $short_desc, $current_user_id, $product_id]);
                $target_id = $product_id;
                $action_audit = 'update';
            }

            // Reemplaza el bloque de 'Category mapping' por este:
            if ($category_id) {
                // Borramos la relación vieja en product_category_map
                $conn->prepare("DELETE FROM product_category_map WHERE product_id = ?")->execute([$target_id]);
                
                // Insertamos la nueva relación
                $conn->prepare("INSERT INTO product_category_map (product_id, category_id, is_primary) VALUES (?, ?, 1)")
                    ->execute([$target_id, $category_id]);
            }

            // --- PROCESAMIENTO DE IMAGEN ---
            $db_image_path = null;
            if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] === 0) {
                $ext = strtolower(pathinfo($_FILES['prod_image']['name'], PATHINFO_EXTENSION));
                $new_name = 'prod_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/images/products/'; 
                
                // Crea la carpeta si no existe
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                
                // Mueve la imagen a la carpeta
                if (move_uploaded_file($_FILES['prod_image']['tmp_name'], $target_dir . $new_name)) {
                    $db_image_path = 'images/products/' . $new_name;
                    
                    // Verifica si ya tenía una imagen principal y la elimina físicamente
                    $stmt_img = $conn->prepare("SELECT url FROM product_images WHERE product_id = ? AND is_primary = 1");
                    $stmt_img->execute([$target_id]);
                    $old_img = $stmt_img->fetchColumn();
                    if ($old_img) {
                        deletePhysicalImage(__DIR__ . '/' . $old_img);
                    }

                    // Actualiza la base de datos (usando la columna 'url')
                    $conn->prepare("DELETE FROM product_images WHERE product_id = ? AND is_primary = 1")->execute([$target_id]);
                    $conn->prepare("INSERT INTO product_images (product_id, url, is_primary) VALUES (?, ?, 1)")->execute([$target_id, $db_image_path]);
                }
            }

            $conn->commit();
            $new_values_audit = ['name' => $name, 'sku' => $sku, 'sale_price' => $sale_price, 'is_featured' => $is_featured];
            registerAudit($conn, $current_user_id, 'products', $target_id, $action_audit, $old_data, $new_values_audit);
            $system_message = "Product processed successfully.";
        }
        
        elseif (isset($_POST['btn_delete_product'])) {
            $product_id = $_POST['del_id'];
            $stmt_fetch = $conn->prepare("SELECT p.*, pi.image_path FROM products p LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.id = ?");
            $stmt_fetch->execute([$product_id]);
            $product_data = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
            
            if ($product_data) {
                if (!empty($product_data['image_path'])) deletePhysicalImage(__DIR__ . '/' . $product_data['image_path']);
                $conn->prepare("DELETE FROM products WHERE id = ?")->execute([$product_id]);
                registerAudit($conn, $current_user_id, 'products', $product_id, 'delete', $product_data, []);
                $system_message = "Product deleted successfully.";
            }
        }
    } catch (PDOException $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        $system_message = "System error: " . $e->getMessage();
    }
}

// =====================================================
// STRUCTURAL NOTE: DATA FETCHING FOR UI
// =====================================================
// REEMPLAZA ESTA FUNCIÓN COMPLETA EN productos.php
function getAllProductsUnified($conn, $org_id) {
    try {
        // Corrección: Usamos MAX(pi.url) AS primary_image
        $sql = "SELECT p.*, MAX(pi.url) AS primary_image, GROUP_CONCAT(c.name SEPARATOR ', ') AS categories
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
        // Si hay error, lo registramos para que no falle silenciosamente
        error_log("Error al obtener productos: " . $e->getMessage());
        return []; 
    }
}

function getCategoriesList($conn, $org_id) {
    try {
        // Leemos de product_categories filtrando por tu organización
        $stmt = $conn->prepare("SELECT id, name FROM product_categories WHERE organization_id = ? AND is_active = 1 ORDER BY name ASC");
        $stmt->execute([$org_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) { return []; }
}

$categories_list = getCategoriesList($conn, $organization_id);

$products_list = getAllProductsUnified($conn, $organization_id);
// Obtener datos de la organización (Para el Header)
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

// Función helper
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
    
    <style>
        :root {
            --primary: <?php echo $org['primary_color']; ?>;
            --primary-dark: <?php echo $org['secondary_color']; ?>;
            --primary-light: rgba(16, 185, 129, 0.1);
            --primary-hover: rgba(16, 185, 129, 0.05);
            
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --white: #ffffff;
            
            --sidebar-width: 280px;
            --header-height: 72px;
            
            --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-sans);
            background: var(--gray-50);
            color: var(--gray-900);
            -webkit-font-smoothing: antialiased;
        }
        
        .app-container { display: flex; min-height: 100vh; }
        .main-content { flex: 1; margin-left: var(--sidebar-width); display: flex; flex-direction: column; }

        /* SIDEBAR COMPLETO RESTAURADO */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--white);
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow: hidden;
            z-index: 40;
        }
        
        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0 1.75rem;
            border-bottom: 1px solid var(--gray-200);
            position: relative;
        }
        
        .sidebar-logo-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            flex-shrink: 0;
            position: relative;
        }
        
        .sidebar-logo { width: 24px; height: 24px; object-fit: contain; }
        .sidebar-brand { flex: 1; min-width: 0; }
        .sidebar-title { font-size: 1.125rem; font-weight: 600; color: var(--gray-900); line-height: 1.2; }
        .sidebar-tagline { font-size: 0.75rem; color: var(--gray-500); margin-top: 0.125rem; }
        
        .sidebar-stats {
            padding: 1rem 1.75rem;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .stats-grid-sidebar { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .stat-item-sidebar { background: var(--white); border: 1px solid var(--gray-200); border-radius: 8px; padding: 0.75rem; display: flex; flex-direction: column; }
        .stat-value-sidebar { font-size: 1.25rem; font-weight: 600; color: var(--gray-900); line-height: 1; margin-bottom: 0.25rem; }
        .stat-label-sidebar { font-size: 0.6875rem; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em; }
        
        .sidebar-nav { flex: 1; padding: 1.5rem 1rem; overflow-y: auto; }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 3px; }
        
        .nav-section { margin-bottom: 1.75rem; }
        .nav-section:last-child { margin-bottom: 0; }
        .nav-section-title { font-size: 0.6875rem; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.08em; padding: 0 0.75rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
        .nav-section-title::before { content: ''; width: 12px; height: 2px; background: var(--gray-300); border-radius: 1px; }
        
        .nav-items { display: flex; flex-direction: column; gap: 0.25rem; }
        .nav-item { display: flex; align-items: center; gap: 0.875rem; padding: 0.75rem 0.75rem; color: var(--gray-700); text-decoration: none; font-size: 0.9375rem; font-weight: 500; transition: all 0.2s ease; border-radius: 8px; position: relative; cursor: pointer; }
        .nav-item::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 3px; height: 0; background: var(--primary); border-radius: 0 2px 2px 0; transition: height 0.2s ease; }
        .nav-item:hover { background: var(--primary-hover); color: var(--gray-900); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-item.active::before { height: 20px; }
        
        .nav-icon { width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .nav-label { flex: 1; }
        .nav-badge { padding: 0.125rem 0.5rem; font-size: 0.6875rem; font-weight: 600; background: var(--gray-100); color: var(--gray-600); border-radius: 10px; line-height: 1.4; }
        .nav-item.active .nav-badge { background: var(--primary); color: var(--white); }
        .nav-arrow { width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: var(--gray-400); transition: transform 0.2s ease; }
        .nav-item.has-submenu.open .nav-arrow { transform: rotate(90deg); }
        
        .nav-submenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; margin-left: 2.25rem; margin-top: 0.25rem; }
        .nav-submenu.open { max-height: 500px; }
        .nav-subitem { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; color: var(--gray-600); text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.15s ease; border-radius: 6px; margin-bottom: 0.125rem; position: relative; }
        .nav-subitem::before { content: ''; width: 4px; height: 4px; background: var(--gray-400); border-radius: 50%; flex-shrink: 0; }
        .nav-subitem:hover { background: var(--primary-hover); color: var(--gray-900); }
        .nav-subitem:hover::before { background: var(--primary); }
        .nav-subitem.active { background: var(--primary-light); color: var(--primary); }
        .nav-subitem.active::before { background: var(--primary); }
        
        .sidebar-footer { padding: 1rem 1.75rem; border-top: 1px solid var(--gray-200); background: var(--gray-50); }
        .sidebar-user { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--white); border: 1px solid var(--gray-200); border-radius: 10px; cursor: pointer; transition: all 0.15s ease; }
        .sidebar-user:hover { border-color: var(--gray-300); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); }
        .sidebar-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: var(--white); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; font-weight: 600; flex-shrink: 0; position: relative; }
        .sidebar-avatar::after { content: ''; position: absolute; bottom: -2px; right: -2px; width: 10px; height: 10px; background: #10b981; border: 2px solid var(--white); border-radius: 50%; }
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name { font-size: 0.875rem; font-weight: 600; color: var(--gray-900); line-height: 1.2; }
        .sidebar-user-role { font-size: 0.75rem; color: var(--gray-500); margin-top: 0.125rem; }
        .sidebar-user-arrow { width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: var(--gray-400); }

        /* HEADER */
        .header {
            height: var(--header-height);
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--gray-600); }
        .breadcrumb-active { color: var(--gray-900); font-weight: 500; }
        
        /* PAGE CONTENT */
        .content { padding: 1.5rem; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 600; color: var(--gray-900); display: flex; align-items: center; gap: 0.75rem; }
        .page-title i { color: var(--primary); }
        
        /* CARD Y FILTROS */
        .card {
            background: var(--white); border-radius: 8px; border: 1px solid var(--gray-200);
            overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 2rem;
        }
        .card-header {
            padding: 1rem 1.25rem; border-bottom: 1px solid var(--gray-200); display: flex;
            justify-content: space-between; align-items: center; gap: 1rem; background: var(--white);
        }
        
        .search-box { position: relative; }
        .search-box input {
            padding: 0.5rem 1rem 0.5rem 2.25rem; border: 1px solid var(--gray-300); border-radius: 6px;
            font-size: 0.875rem; width: 240px; background: var(--white);
        }
        .search-box i {
            position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); font-size: 0.875rem;
        }
        
        /* TABLA ESTILO PRO */
        .table-responsive { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.05em; color: var(--gray-500); background: var(--gray-50); border-bottom: 1px solid var(--gray-200);
        }
        .data-table td {
            padding: 0.875rem 1rem; border-bottom: 1px solid var(--gray-200); font-size: 0.875rem;
            color: var(--gray-700); vertical-align: middle;
        }
        .data-table tbody tr:hover { background: var(--gray-50); }

        /* Alert */
        .alert-floating {
            position: fixed; top: 1rem; right: 1rem; z-index: 100;
            padding: 1rem; border-radius: 8px; background: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary);
            display: flex; gap: 10px; align-items: center;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn { from { transform: translateX(100%); } to { transform: translateX(0); } }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
        }
    </style>
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
                    <i class="fas fa-box-open text-emerald-600"></i> Global Inventory
                </h1>
                <button onclick="openProductModal(true)" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>

            <div class="card">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product Details</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Base Price</th>
                                <th>Sale Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products_list as $product): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <img src="<?php echo $product['primary_image'] ?: 'assets/images/no-image.png'; ?>" class="w-10 h-10 rounded-md object-cover border border-gray-200">
                                            <div>
                                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                                <div class="text-xs text-gray-500">SKU: <?php echo htmlspecialchars($product['sku']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="bg-gray-100 px-2.5 py-1 rounded-full text-xs"><?php echo $product['categories'] ?: 'Uncategorized'; ?></span></td>
                                    <td class="font-medium"><?php echo $product['stock_quantity']; ?></td>
                                    <td>$<?php echo number_format($product['base_price'], 2); ?></td>
                                    <td class="text-emerald-600 font-bold"><?php echo $product['sale_price'] ? '$'.number_format($product['sale_price'], 2) : '-'; ?></td>
                                    <td>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $product['status']==='active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'; ?>">
                                            <?php echo ucfirst($product['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)" class="p-2 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition"><i class="fas fa-pen"></i></button>
                                            <form method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                                <input type="hidden" name="del_id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" name="btn_delete_product" class="p-2 bg-red-50 text-red-600 rounded hover:bg-red-100 transition"><i class="fas fa-trash-alt"></i></button>
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

        <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Product Information</h3>
                    <button type="button" onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                
                <form method="POST" enctype="multipart/form-data" class="p-6" id="productForm">
                    <input type="hidden" name="edit_id" id="edit_id">

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                                <input type="text" name="prod_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                                    <input type="text" name="prod_sku" placeholder="Auto" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Base Price *</label>
                                    <input type="number" step="0.01" name="prod_price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría (Género) *</label>
                                <select name="prod_category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                    <option value="">Seleccionar Género</option>
                                        <?php foreach ($categories_list as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                        <?php endforeach; ?>
                                </select>
                                <p class="text-[10px] text-gray-500 mt-1">Selecciona Hombre para Men Config/Page o Mujer para Women Config/Page.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                                <input type="number" name="prod_stock" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                                <select name="prod_visibility" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="visible">Visible (Everywhere)</option>
                                    <option value="catalog">Catalog Only</option>
                                    <option value="hidden">Hidden</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="prod_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen del Producto</label>
                            <input type="file" name="prod_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all border border-gray-300 rounded-lg">
                            <p class="text-[10px] text-gray-400 mt-1">Sube una imagen representativa.</p>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-200">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-amber-800 mb-4">
                            <input type="checkbox" name="is_on_sale" id="saleToggle" onchange="toggleSaleFields()" class="w-5 h-5 rounded text-amber-600">
                            <i class="fas fa-tag"></i> Apply Sale Offer?
                        </label>
                        
                        <div id="saleFields" class="grid grid-cols-3 gap-4 hidden">
                            <div>
                                <label class="block text-xs font-bold text-amber-700 uppercase">Sale Price</label>
                                <input type="number" step="0.01" name="prod_sale_price" class="w-full p-2 border border-amber-200 rounded-lg outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-amber-700 uppercase">Start Date</label>
                                <input type="datetime-local" name="sale_start_at" class="w-full p-2 border border-amber-200 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-amber-700 uppercase">End Date</label>
                                <input type="datetime-local" name="sale_end_at" class="w-full p-2 border border-amber-200 rounded-lg text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                        <label class="flex items-center gap-2 cursor-pointer font-medium text-gray-700 text-sm">
                            <input type="checkbox" name="prod_featured" class="w-4 h-4 rounded text-emerald-600">
                            <i class="fas fa-star text-amber-400"></i> Mark as Featured Product (Atelier Peru Home)
                        </label>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea name="prod_short_desc" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeProductModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" id="submitBtn" name="btn_save_product" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    </div>

    <script>
        function toggleSaleFields() {
            const isChecked = document.getElementById('saleToggle').checked;
            document.getElementById('saleFields').classList.toggle('hidden', !isChecked);
        }

        function openProductModal(isNew = true) {
            if (isNew) {
                document.getElementById('productForm').reset();
                document.getElementById('edit_id').value = '';
                document.getElementById('modalTitle').innerText = 'Add New Product';
                document.getElementById('submitBtn').name = 'btn_save_product';
                document.getElementById('saleFields').classList.add('hidden');
            }
            document.getElementById('productModal').classList.remove('hidden');
        }

        function closeProductModal() { document.getElementById('productModal').classList.add('hidden'); }

        function editProduct(data) {
            openProductModal(false);
            document.getElementById('modalTitle').innerText = 'Edit Product Details';
            document.getElementById('submitBtn').name = 'btn_update_product';
            
            document.getElementById('edit_id').value = data.id;
            document.querySelector('[name="prod_name"]').value = data.name;
            document.querySelector('[name="prod_sku"]').value = data.sku;
            document.querySelector('[name="prod_price"]').value = data.base_price;
            document.querySelector('[name="prod_stock"]').value = data.stock_quantity;
            document.querySelector('[name="prod_status"]').value = data.status;
            document.querySelector('[name="prod_visibility"]').value = data.visibility || 'visible';
            document.querySelector('[name="prod_short_desc"]').value = data.short_description || '';
            document.querySelector('[name="prod_featured"]').checked = (data.is_featured == 1);

            const hasSale = data.sale_price !== null && data.sale_price > 0;
            document.getElementById('saleToggle').checked = hasSale;
            document.querySelector('[name="prod_sale_price"]').value = data.sale_price || '';
            
            if(data.sale_start_at) document.querySelector('[name="sale_start_at"]').value = data.sale_start_at.replace(' ', 'T').substring(0, 16);
            if(data.sale_end_at) document.querySelector('[name="sale_end_at"]').value = data.sale_end_at.replace(' ', 'T').substring(0, 16);
            
            toggleSaleFields();
        }
    </script>
</body>
</html>