<?php
// ===============================
// 1. CONEXIÓN A LA BASE DE DATOS
// ===============================
require_once __DIR__ . '/../System/conexion/conexion.php';

// ===============================
// 2. FUNCIÓN PARA OBTENER DATOS
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

// ===============================
// 3. VARIABLES DE TEXTO
// ===============================
// Usamos el operador ?? para los textos por defecto (Fallback)
$heroLabel    = $config['hero_label'] ?? 'Primavera/Verano';
$heroTitle    = $config['hero_title'] ?? 'Nueva Colección';
$heroSubtitle = $config['hero_subtitle'] ?? 'Piezas únicas para una nueva temporada';

$prodLabel    = $config['prod_label'] ?? 'Nuevas llegadas';
$prodTitle    = $config['prod_title'] ?? 'Lo más reciente';

// ===============================
// 4. LÓGICA DE IMÁGENES
// ===============================

// Ruta web relativa desde donde se carga este archivo
$rutaWeb = 'images/new_collection/'; 
// Ruta física para verificar si el archivo existe en el servidor
$rutaFisicaBase = __DIR__ . '/../images/new_collection/';

// Función Helper para validar imagen (Misma lógica que en home.php)
function validarImagenNC($nombreBD, $rutaFisica, $rutaWeb, $defaultUrl) {
    if (!empty($nombreBD) && file_exists($rutaFisica . $nombreBD)) {
        return $rutaWeb . $nombreBD;
    }
    return $defaultUrl;
}

// Configuración de la imagen del Hero
$heroImage = validarImagenNC(
    $config['hero_image'] ?? '', 
    $rutaFisicaBase, 
    $rutaWeb, 
    'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600&q=80'
);
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
            <?php
            $productos = [
                ['nombre' => 'Vestido Midi de Seda', 'precio' => 245, 'marca' => 'Atelier Studio', 
                 'imagen' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=600&q=80',
                 'badge' => 'Nuevo'],
                ['nombre' => 'Blazer Oversize', 'precio' => 320, 'marca' => 'Premium Line',
                 'imagen' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80',
                 'badge' => 'Nuevo'],
                ['nombre' => 'Camisa de Lino', 'precio' => 95, 'marca' => 'Essentials',
                 'imagen' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&q=80',
                 'badge' => 'Nuevo'],
                ['nombre' => 'Pantalón Wide Leg', 'precio' => 135, 'marca' => 'Atelier Studio',
                 'imagen' => 'https://images.unsplash.com/photo-1624206112918-f140f087f9b5?w=600&q=80',
                 'badge' => 'Nuevo'],
            ];
            
            foreach ($productos as $producto): ?>
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="<?php echo $producto['imagen']; ?>" 
                         alt="<?php echo $producto['nombre']; ?>">
                    <span class="product-badge"><?php echo $producto['badge']; ?></span>
                    <button class="quick-add">Agregar al carrito</button>
                </div>
                <div class="product-info">
                    <p class="product-brand"><?php echo $producto['marca']; ?></p>
                    <p class="product-name"><?php echo $producto['nombre']; ?></p>
                    <p class="product-price">$<?php echo $producto['precio']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>