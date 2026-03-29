<?php
// Incluye el archivo de conexión a la base de datos para habilitar las consultas
require_once __DIR__ . '/../System/conexion/conexion.php';

function obtenerConfiguracionNewColl($conn) {
    try {
        // Consulta la configuración visual de la nueva colección limitando a un solo registro
        $sql = "SELECT * FROM web_design_new_collection WHERE id = 1 LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (Exception $e) {
        return [];
    }
}

$config = obtenerConfiguracionNewColl($conn);

$heroLabel    = $config['hero_label'] ?? 'Primavera/Verano';
$heroTitle    = $config['hero_title'] ?? 'Nueva Colección';
$heroSubtitle = $config['hero_subtitle'] ?? 'Piezas únicas para una nueva temporada';
$prodLabel    = $config['prod_label'] ?? 'Nuevas llegadas';
$prodTitle    = $config['prod_title'] ?? 'Lo más reciente';

$rutaWeb = 'images/new_collection/'; 
$rutaFisicaBase = __DIR__ . '/../images/new_collection/';

function validarImagenNC($nombreBD, $rutaFisica, $rutaWeb, $defaultUrl) {
    // Verifica que el archivo de imagen exista físicamente antes de asignar su ruta web
    if (!empty($nombreBD) && file_exists($rutaFisica . $nombreBD)) {
        return $rutaWeb . $nombreBD;
    }
    return $defaultUrl;
}

$heroImage = validarImagenNC(
    $config['hero_image'] ?? '', 
    $rutaFisicaBase, 
    $rutaWeb, 
    'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600&q=80'
);

// Obtiene los 8 productos activos más recientes junto con su categoría y precio de oferta si aplica
$sqlUnified = "
    SELECT 
        p.id, 
        p.name, 
        p.base_price as price, 
        p.sale_price as current_sale_price,
        p.created_at,
        pi.url as image,
        (SELECT name FROM product_categories cat 
         JOIN product_category_map pcm ON cat.id = pcm.category_id 
         WHERE pcm.product_id = p.id LIMIT 1) as category_name
    FROM products p
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE p.status = 'active' AND p.visibility = 'visible' AND p.is_active = 1
    ORDER BY p.created_at DESC
    LIMIT 8
";

try {
    $stmt = $conn->prepare($sqlUnified);
    $stmt->execute();
    $productosRecientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productosRecientes = [];
}
?>

<section class="page-hero">
    <img src="<?= htmlspecialchars($heroImage); ?>" 
         alt="Nueva Colección" class="page-hero-image">
    
    <div class="page-hero-overlay"></div>
    
    <div class="page-hero-content">
        <p class="hero-label"><?= htmlspecialchars($heroLabel); ?></p>
        <h1><?= nl2br(htmlspecialchars($heroTitle)); ?></h1>
        <p><?= nl2br(htmlspecialchars($heroSubtitle)); ?></p>
    </div>
</section>

<section class="products">
    <div class="products-container">
        <div class="products-header">
            <div>
                <p class="section-label"><?= htmlspecialchars($prodLabel); ?></p>
                <h2 class="section-title"><?= htmlspecialchars($prodTitle); ?></h2>
            </div>
        </div>
        
        <div class="products-grid">
            <?php if (count($productosRecientes) > 0): ?>
                <?php foreach ($productosRecientes as $prod): 
                
                $imgSrc = !empty($prod['image']) ? "../" . $prod['image'] : "../images/default-product.jpg";
                
                // Determina si el producto está en oferta para definir el precio actual a mostrar y el original a tachar
                $isSale = (!empty($prod['current_sale_price']) && $prod['current_sale_price'] > 0);
                $displayPrice = $isSale ? $prod['current_sale_price'] : $prod['price'];
                $oldPrice = $isSale ? $prod['price'] : null;

                $dateCreated = new DateTime($prod['created_at']);
                $now = new DateTime();
                $diff = $now->diff($dateCreated)->days;

                $badgeText = '';
                $badgeClass = '';

                // Asigna la etiqueta visual correspondiente priorizando las ofertas sobre los productos nuevos
                if ($isSale) {
                    $badgeText = 'Oferta';
                    $badgeClass = 'sale';
                } elseif ($diff <= 3) {
                    $badgeText = 'Nuevo';
                    $badgeClass = 'new';
                }
            ?>
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="<?= htmlspecialchars($imgSrc); ?>" 
                        alt="<?= htmlspecialchars($prod['name']); ?>">
                    
                    <?php if (!empty($badgeText)): ?>
                        <span class="product-badge <?= $badgeClass; ?>"><?= htmlspecialchars($badgeText); ?></span>
                    <?php endif; ?>
                    
                    <button class="quick-add">Ver Detalles</button>
                </div>
                
                <div class="product-info">
                    <p class="product-brand"><?= htmlspecialchars($prod['category_name'] ?? 'Colección'); ?></p>
                    <p class="product-name"><?= htmlspecialchars($prod['name']); ?></p>
                    <p class="product-price">
                        $<?= number_format($displayPrice, 2); ?>
                        
                        <?php if ($oldPrice): ?>
                            <span class="old-price" style="text-decoration: line-through; color: #999; font-size: 0.9em; margin-left: 5px;">
                                $<?= number_format($oldPrice, 2); ?>
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <p>Aún no hay productos recientes para mostrar.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>