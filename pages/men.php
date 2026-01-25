<?php
// 1. CONEXIÓN A BASE DE DATOS
// Ajustamos la ruta para salir de "pages" y entrar a "System/conexion"
require_once __DIR__ . '/../System/conexion/conexion.php';

// 2. OBTENER PRODUCTOS DESDE LA TABLA
try {
    $stmt = $conn->query("SELECT * FROM web_design_men ORDER BY id DESC");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $productos = [];
}
?>

<section class="page-hero">
    <img src="https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=1600&q=80" 
         alt="Hombre" class="page-hero-image">
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
        <h1>Colección Hombre</h1>
        <p>Estilo contemporáneo para el hombre moderno</p>
    </div>
</section>

<section class="products">
    <div class="products-container">
        <div class="products-grid">
            <?php 
            // Verificamos si hay productos en la BD
            if (!empty($productos)): 
                foreach ($productos as $producto): 
                    // IMPORTANTE: Ajuste de ruta de imagen.
                    // En la BD se guarda como: "images/men/foto.jpg"
                    // Desde la carpeta "pages/", debemos subir un nivel: "../images/men/foto.jpg"
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
