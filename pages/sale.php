<?php
// 1. CONEXIÓN 
require_once __DIR__ . '/../System/conexion/conexion.php';

// 2. CONSULTAR DISEÑO (Tabla: web_design_sale, ID 1)
$sqlDiseno = "SELECT * FROM web_design_sale WHERE id = 1 LIMIT 1";
$stmtD = $conn->query($sqlDiseno);
$diseno = $stmtD->fetch(PDO::FETCH_ASSOC);

// Valores por defecto
$hero_img   = "https://images.unsplash.com/photo-1607083206139-7c5b07e66ac3?w=1600&q=80"; 
$hero_title = "SALE";
$hero_sub   = "Ofertas imperdibles";

if ($diseno) {
    if (!empty($diseno['hero_image'])) { $hero_img = "../" . $diseno['hero_image']; }
    if (!empty($diseno['hero_title'])) { $hero_title = $diseno['hero_title']; }
    if (!empty($diseno['hero_subtitle'])) { $hero_sub = $diseno['hero_subtitle']; }
}

// 3. CONSULTAR PRODUCTOS (Tabla: web_design_sale_products)
$sqlProd = "SELECT * FROM web_design_sale_products ORDER BY id DESC";
$stmtP = $conn->query($sqlProd);
$productosSale = $stmtP->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="page-hero sale-hero">
    <img src="<?php echo htmlspecialchars($hero_img); ?>" alt="Sale" class="page-hero-image">
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
        <h1><?php echo htmlspecialchars($hero_title); ?></h1>
        <p class="sale-discount"><?php echo htmlspecialchars($hero_sub); ?></p>
    </div>
</section>

<section class="products">
    <div class="products-container">
        <div class="products-grid">
            <?php if (!empty($productosSale)): ?>
                <?php foreach ($productosSale as $producto): 
                    $rutaImagen = "../" . $producto['image'];
                ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="<?php echo htmlspecialchars($rutaImagen); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
                        
                        <?php if (!empty($producto['discount'])): ?>
                            <span class="product-badge sale"><?php echo htmlspecialchars($producto['discount']); ?></span>
                        <?php endif; ?>
                        
                        <button class="quick-add">Agregar al carrito</button>
                    </div>
                    <div class="product-info">
                        <p class="product-brand"><?php echo htmlspecialchars($producto['brand']); ?></p>
                        <p class="product-name"><?php echo htmlspecialchars($producto['name']); ?></p>
                        <p class="product-price">
                            $<?php echo number_format($producto['price'], 2); ?>
                            
                            <?php if (!empty($producto['old_price']) && $producto['old_price'] > 0): ?>
                                <span class="old-price">
                                    $<?php echo number_format($producto['old_price'], 2); ?>
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align:center; padding: 3rem; color: #666;">No hay ofertas disponibles.</p>
            <?php endif; ?>
        </div>
    </div>
</section>