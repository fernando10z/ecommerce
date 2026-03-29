<?php
// Incluye el archivo de conexión a la base de datos para habilitar las consultas
require_once __DIR__ . '/../System/conexion/conexion.php';

// Consulta la configuración visual de la sección de ofertas limitando a un único registro
$sqlDiseno = "SELECT * FROM web_design_sale WHERE id = 1 LIMIT 1";
$stmtD = $conn->query($sqlDiseno);
$diseno = $stmtD->fetch(PDO::FETCH_ASSOC);

$hero_img   = "https://images.unsplash.com/photo-1607083206139-7c5b07e66ac3?w=1600&q=80"; 
$hero_title = "SALE";
$hero_sub   = "Ofertas imperdibles";

// Reemplaza los valores del banner principal si existen registros personalizados en la base de datos
if ($diseno) {
    if (!empty($diseno['hero_image'])) { $hero_img = "../" . $diseno['hero_image']; }
    if (!empty($diseno['hero_title'])) { $hero_title = $diseno['hero_title']; }
    if (!empty($diseno['hero_subtitle'])) { $hero_sub = $diseno['hero_subtitle']; }
}

try {
    // Obtiene productos en oferta activos y que sean visibles globalmente (excluye 'catalog' y 'hidden')
    $sql = "SELECT p.id, p.name, p.base_price, p.sale_price, pi.url as image 
            FROM products p
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE p.sale_price IS NOT NULL 
              AND p.sale_price > 0 
              AND p.sale_price < p.base_price 
              AND p.status = 'active' 
              AND p.visibility = 'visible'
              AND p.is_active = 1
            ORDER BY p.created_at DESC";
    $stmtP = $conn->query($sql);
    $productosSale = $stmtP->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productosSale = [];
}
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
                    $rutaImagen = !empty($producto['image']) ? "../" . $producto['image'] : "../images/default-product.jpg";
                    
                    // Verifica la existencia de una oferta válida para calcular el porcentaje de descuento y el precio final
                    $tiene_oferta = !empty($producto['sale_price']) && $producto['sale_price'] > 0 && $producto['sale_price'] < $producto['base_price'];
                    $precio_actual = $tiene_oferta ? $producto['sale_price'] : $producto['base_price'];
                ?>
                <div class="product-card">
                    <div class="product-image-wrapper" style="position: relative;">
                        
                        <?php if ($tiene_oferta): 
                            $porcentaje = round((($producto['base_price'] - $producto['sale_price']) / $producto['base_price']) * 100);
                        ?>
                            <span style="position: absolute; top: 10px; left: 10px; background-color: #b93b3b; color: white; padding: 4px 8px; font-size: 11px; font-weight: bold; letter-spacing: 1px;">
                                <?php echo $porcentaje; ?>%
                            </span>
                        <?php else: ?>
                            <span style="position: absolute; top: 10px; left: 10px; background-color: white; color: black; padding: 4px 8px; font-size: 11px; font-weight: bold; letter-spacing: 1px;">
                                NUEVO
                            </span>
                        <?php endif; ?>

                        <img src="<?php echo htmlspecialchars($rutaImagen); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
                        <button class="quick-add">Agregar al carrito</button>
                    </div>
                    
                    <div class="product-info" style="padding-top: 15px; text-align: left;">
                        <p class="product-category" style="font-size: 10px; color: #888; text-transform: uppercase; margin-bottom: 5px;">CATEGORÍA</p>
                        <p class="product-name" style="font-size: 14px; font-weight: 500; margin-bottom: 8px;">
                            <?php echo htmlspecialchars($producto['name']); ?>
                        </p>
                        
                        <p class="product-price" style="font-size: 14px; font-weight: bold; color: #111;">
                            $<?php echo number_format($precio_actual, 2); ?>
                            
                            <?php if ($tiene_oferta): ?>
                                <span class="old-price" style="text-decoration: line-through; color: #aaa; font-size: 12px; margin-left: 8px; font-weight: normal;">
                                    $<?php echo number_format($producto['base_price'], 2); ?>
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