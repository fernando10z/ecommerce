<?php
// 1. CONEXIÓN A BASE DE DATOS
require_once __DIR__ . '/../System/conexion/conexion.php';

// 2. CONSULTAR DISEÑO
$sqlDiseno = "SELECT * FROM web_design_women WHERE id = 1 LIMIT 1";
$stmtD = $conn->query($sqlDiseno);
$diseno = $stmtD->fetch(PDO::FETCH_ASSOC);

$hero_img = "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=1600&q=80"; 
$hero_title = "Colección Mujeres"; 
$hero_sub = "Estilo contemporáneo para la mujer moderna"; 

if ($diseno) {
    if (!empty($diseno['hero_imagen'])) { $hero_img = "../" . $diseno['hero_imagen']; }
    if (!empty($diseno['hero_titulo'])) { $hero_title = $diseno['hero_titulo']; }
    if (!empty($diseno['hero_subtitulo'])) { $hero_sub = $diseno['hero_subtitulo']; }
}

// 3. CONSULTAR PRODUCTOS (Corregido con las tablas de tu BD)
try {
    $sql = "SELECT p.id, p.name, p.base_price as price, pi.url as image 
            FROM products p
            INNER JOIN product_category_map pcm ON p.id = pcm.product_id
            INNER JOIN product_categories c ON pcm.category_id = c.id
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE (c.name = 'Mujer' OR c.slug IN ('mujer', 'women')) AND p.status = 'active'
            ORDER BY p.created_at DESC";
    $stmtP = $conn->query($sql);
    $productos = $stmtP->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $productos = [];
}
?>

<section class="page-hero">
    <img src="<?php echo htmlspecialchars($hero_img); ?>" alt="Mujeres" class="page-hero-image">
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
        <h1><?php echo htmlspecialchars($hero_title); ?></h1>
        <p><?php echo htmlspecialchars($hero_sub); ?></p>
    </div>
</section>

<section class="products">
    <div class="products-container">
        <div class="products-grid">
            <?php if (!empty($productos)): 
                foreach ($productos as $producto): 
                    // Agregamos "System/" a la ruta para que apunte a la carpeta correcta
                    $rutaImagen = !empty($producto['image']) ? "../System/" . $producto['image'] : "../System/images/default-product.jpg";
            ?>
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="<?php echo htmlspecialchars($rutaImagen); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
                    <button class="quick-add">Agregar al carrito</button>
                </div>
                <div class="product-info">
                    <p class="product-name"><?php echo htmlspecialchars($producto['name']); ?></p>
                    <p class="product-price">$<?php echo number_format($producto['price'], 2); ?></p>
                </div>
            </div>
            <?php endforeach; else: ?>
                <p style="grid-column: 1/-1; text-align:center; color:#666;">No hay productos registrados en este momento.</p>
            <?php endif; ?>
        </div>
    </div>
</section>