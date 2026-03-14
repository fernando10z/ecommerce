<?php
// Incluye el archivo de conexión a la base de datos para permitir las consultas posteriores
require_once __DIR__ . '/../System/conexion/conexion.php';

// Consulta los datos de diseño personalizados para la sección de hombres limitando a un solo registro
$sqlDiseno = "SELECT * FROM web_design_men WHERE id = 1 LIMIT 1";
$stmtD = $conn->query($sqlDiseno);
$diseno = $stmtD->fetch(PDO::FETCH_ASSOC);

// Asigna valores predeterminados para el banner principal por si la base de datos no tiene información
$hero_img   = "https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=1600&q=80";
$hero_title = "Colección Hombre";
$hero_sub   = "Estilo contemporáneo";

if ($diseno) {
    if (!empty($diseno['hero_imagen'])) { $hero_img = "../" . $diseno['hero_imagen']; }
    if (!empty($diseno['hero_titulo'])) { $hero_title = $diseno['hero_titulo']; }
    if (!empty($diseno['hero_subtitulo'])) { $hero_sub = $diseno['hero_subtitulo']; }
}

try {
    // Obtiene productos activos y visibles (en tienda o solo catálogo) para la categoría hombre
    $sql = "SELECT p.id, p.name, p.base_price, p.sale_price, pi.url as image
            FROM products p
            INNER JOIN product_category_map pcm ON p.id = pcm.product_id
            INNER JOIN product_categories c ON pcm.category_id = c.id
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE (c.name = 'Hombre' OR c.slug IN ('hombre', 'men')) 
              AND p.status = 'active' 
              AND p.visibility IN ('visible', 'catalog')
            ORDER BY p.created_at DESC";
    $stmtP = $conn->query($sql);
    $productos = $stmtP->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productos = [];
}
?>

<section class="page-hero">
    <img src="<?php echo htmlspecialchars($hero_img); ?>" alt="Banner Hombre" class="page-hero-image">
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
        <h1><?php echo htmlspecialchars($hero_title); ?></h1>
        <p><?php echo htmlspecialchars($hero_sub); ?></p>
    </div>
</section>

<section class="products">
    <div class="products-container">
        <div class="products-grid">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): 
                    $rutaImagen = !empty($producto['image']) ? "../" . $producto['image'] : "../images/default-product.jpg";
                    
                    // Verifica si el precio de oferta es válido y menor al precio base para determinar el precio final a mostrar
                    $tiene_oferta = !empty($producto['sale_price']) && $producto['sale_price'] > 0 && $producto['sale_price'] < $producto['base_price'];
                    $precio_actual = $tiene_oferta ? $producto['sale_price'] : $producto['base_price'];
                ?>
                <div class="product-card">
                    <div class="product-image-wrapper" style="position: relative;">
                        
                        <?php if ($tiene_oferta): 
                            $porcentaje = round((($producto['base_price'] - $producto['sale_price']) / $producto['base_price']) * 100);
                        ?>
                            <span style="position: absolute; top: 10px; left: 10px; background-color: #b93b3b; color: white; padding: 4px 8px; font-size: 11px; font-weight: bold; letter-spacing: 1px; z-index: 2;">
                                <?php echo $porcentaje; ?>%
                            </span>
                        <?php else: ?>
                            <span style="position: absolute; top: 10px; left: 10px; background-color: white; color: black; padding: 4px 8px; font-size: 11px; font-weight: bold; letter-spacing: 1px; z-index: 2; border: 1px solid #eee;">
                                NUEVO
                            </span>
                        <?php endif; ?>

                        <img src="<?php echo htmlspecialchars($rutaImagen); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
                        <button class="quick-add">Agregar al carrito</button>
                    </div>
                    
                    <div class="product-info" style="padding-top: 15px; text-align: left;">
                        <p class="product-category" style="font-size: 10px; color: #888; text-transform: uppercase; margin-bottom: 5px;">CASTILLO</p> <p class="product-name" style="font-size: 14px; font-weight: 500; margin-bottom: 8px;">
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
                <p style="grid-column: 1/-1; text-align:center;">Próximamente nuevos productos.</p>
            <?php endif; ?>
        </div>
    </div>
</section>