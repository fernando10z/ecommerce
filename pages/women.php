<?php
// 1. CONEXIÓN A BASE DE DATOS
require_once __DIR__ . '/../System/conexion/conexion.php';

// 2. CONSULTAR DISEÑO (Tabla: web_design_women)
$sqlDiseno = "SELECT * FROM web_design_women WHERE id = 1 LIMIT 1";
$stmtD = $conn->query($sqlDiseno);
$diseno = $stmtD->fetch(PDO::FETCH_ASSOC);

// LÓGICA HERO: Valores por defecto por si la tabla está vacía
$hero_img = "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=1600&q=80"; 
$hero_title = "Colección Mujeres"; 
$hero_sub = "Estilo contemporáneo para la mujer moderna"; 

if ($diseno) {
    if (!empty($diseno['hero_imagen'])) { $hero_img = "../" . $diseno['hero_imagen']; }
    if (!empty($diseno['hero_titulo'])) { $hero_title = $diseno['hero_titulo']; }
    if (!empty($diseno['hero_subtitulo'])) { $hero_sub = $diseno['hero_subtitulo']; }
}

// 3. CONSULTAR PRODUCTOS (Tabla: web_design_women_products)
try {
    $stmtP = $conn->query("SELECT * FROM web_design_women_products ORDER BY id DESC");
    $productos = $stmtP->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $productos = [];
}
?>

<section class="page-hero">
    <img src="<?php echo htmlspecialchars($hero_img); ?>" 
         alt="Mujeres" class="page-hero-image">
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
        <h1><?php echo htmlspecialchars($hero_title); ?></h1>
        <p><?php echo htmlspecialchars($hero_sub); ?></p>
    </div>
</section>

<section class="products">
    <div class="products-container">
        <div class="products-grid">
            <?php 
            // Verificamos si hay productos en la BD
            if (!empty($productos)): 
                foreach ($productos as $producto): 
                    $rutaImagen = "../" . $producto['imagen'];
            ?>
            
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="<?php echo htmlspecialchars($rutaImagen); ?>" 
                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <button class="quick-add">Agregar al carrito</button>
                </div>
                <div class="product-info">
                    <p class="product-brand"><?php echo htmlspecialchars($producto['marca']); ?></p>
                    <p class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                    <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?></p>
                </div>
            </div>

            <?php 
                endforeach; 
            else: 
            ?>
                <p style="grid-column: 1/-1; text-align:center; color:#666;">No hay productos registrados en este momento.</p>
            <?php endif; ?>
        </div>
    </div>
</section>