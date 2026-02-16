<?php
// ===============================
// 1. CONEXIÓN A LA BASE DE DATOS
// ===============================
// Ajusta la ruta si es necesario según tu estructura de carpetas
require_once __DIR__ . '/../System/conexion/conexion.php';

// ===============================
// 2. CONFIGURACIÓN VISUAL (TEXTOS HERO)
// ===============================
function obtenerConfiguracionNewColl($conn) {
    try {
        $sql = "SELECT * FROM web_design_new_collection WHERE id = 1 LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (Exception $e) {
        return [];
    }
}

$config = obtenerConfiguracionNewColl($conn);

// Textos por defecto (Fallback)
$heroLabel    = $config['hero_label'] ?? 'Primavera/Verano';
$heroTitle    = $config['hero_title'] ?? 'Nueva Colección';
$heroSubtitle = $config['hero_subtitle'] ?? 'Piezas únicas para una nueva temporada';
$prodLabel    = $config['prod_label'] ?? 'Nuevas llegadas';
$prodTitle    = $config['prod_title'] ?? 'Lo más reciente';

// ===============================
// 3. LÓGICA DE IMAGEN HERO
// ===============================
$rutaWeb = 'images/new_collection/'; 
$rutaFisicaBase = __DIR__ . '/../images/new_collection/';

function validarImagenNC($nombreBD, $rutaFisica, $rutaWeb, $defaultUrl) {
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

// ===============================
// 4. CONSULTA UNIFICADA (MEN + WOMEN + SALE)
// ===============================
// Esta consulta une las 3 tablas, normaliza los nombres de columnas y ordena por fecha
$sqlUnified = "
    (SELECT id, nombre as name, marca as brand, precio as price, NULL as old_price, NULL as discount, imagen as image, 'men' as category, created_at FROM web_design_men_products)
    UNION ALL
    (SELECT id, nombre as name, marca as brand, precio as price, NULL as old_price, NULL as discount, imagen as image, 'women' as category, created_at FROM web_design_women_products)
    UNION ALL
    (SELECT id, name, brand, price, old_price, discount, image, 'sale' as category, created_at FROM web_design_sale_products)
    ORDER BY created_at DESC
    LIMIT 8
";

try {
    $stmt = $conn->prepare($sqlUnified);
    $stmt->execute();
    $productosRecientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productosRecientes = [];
    // echo "Error: " . $e->getMessage(); // Descomentar para debug
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
                    // Ajuste de ruta de imagen:
                    // En la BD guardas 'images/men/foto.jpg'.
                    // Si este archivo está dentro de una carpeta 'pages' o similar, necesitas salir con '../'
                    // Si estás en la raíz, quita el '../' del inicio.
                    $imgSrc = "../" . $prod['image']; 
                    
                    // Lógica de Badges
                    $badgeText = '';
                    $badgeClass = '';
                    
                    if ($prod['category'] === 'sale' && !empty($prod['discount'])) {
                        $badgeText = $prod['discount'];
                        $badgeClass = 'sale'; // Clase CSS roja usualmente
                    } else {
                        // Si es Men o Women, asumimos que es "Nuevo" porque está en esta lista de recientes
                        $badgeText = 'Nuevo';
                        $badgeClass = 'new'; // Clase CSS que deberías tener (ej. negra o azul)
                    }
                ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="<?= htmlspecialchars($imgSrc); ?>" 
                             alt="<?= htmlspecialchars($prod['name']); ?>"
                             onerror="this.src='https://via.placeholder.com/300x400?text=Sin+Imagen'">
                        
                        <?php if (!empty($badgeText)): ?>
                            <span class="product-badge <?= $badgeClass; ?>"><?= htmlspecialchars($badgeText); ?></span>
                        <?php endif; ?>
                        
                        <button class="quick-add">Ver Detalles</button>
                    </div>
                    
                    <div class="product-info">
                        <p class="product-brand"><?= htmlspecialchars($prod['brand']); ?></p>
                        <p class="product-name"><?= htmlspecialchars($prod['name']); ?></p>
                        <p class="product-price">
                            $<?= number_format($prod['price'], 2); ?>
                            
                            <?php 
                            // Solo mostramos precio anterior si existe y es mayor a 0 (Solo pasa en Sale)
                            if (!empty($prod['old_price']) && $prod['old_price'] > 0): ?>
                                <span class="old-price" style="text-decoration: line-through; color: #999; font-size: 0.9em; margin-left: 5px;">
                                    $<?= number_format($prod['old_price'], 2); ?>
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