<?php
// ===============================
// 1. CONEXIÓN A LA BASE DE DATOS
// ===============================
require_once __DIR__ . '/../System/conexion/conexion.php';

// ===============================
// 2. FUNCIÓN PARA OBTENER DATOS (ACTUALIZADA)
// ===============================
function obtenerConfiguracionHome($conn) {
    try {
        // CAMBIO: Seleccionamos TODO (*) para traer imagen Y textos
        $sql = "SELECT * FROM web_design_home WHERE id = 1 LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        // Si no hay datos, devolvemos un array vacío para evitar errores
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (Exception $e) {
        return [];
    }
}

// Obtenemos todos los datos de la base de datos
$config = obtenerConfiguracionHome($conn);

// ===============================
// 3. VARIABLES DE TEXTO (NUEVO)
// ===============================
// Usamos el operador ?? para poner un texto por defecto si la base de datos está vacía
$heroLabel    = $config['hero_label'] ?? 'Primavera/Verano 2025';
$heroTitle    = $config['hero_title'] ?? 'La elegancia redefinida';
$heroSubtitle = $config['hero_subtitle'] ?? 'Descubre nuestra nueva colección diseñada para la mujer y el hombre contemporáneo.';
$catLabel    = $config['cat_label'] ?? 'CATEGORÍAS';
$catTitle    = $config['cat_title'] ?? 'Explora por estilo';
$newsTitle    = $config['news_title'] ?? 'Únete a nuestra comunidad';
$newsSubtitle = $config['news_subtitle'] ?? 'Recibe acceso anticipado a nuevas colecciones';


// ===============================
// 4. LÓGICA DE IMÁGENES
// ===============================

$rutaWeb = 'images/home/'; 
$rutaFisicaBase = __DIR__ . '/../images/home/';

// --- Función Helper interna para validar imágenes ---
function validarImagen($nombreBD, $rutaFisica, $rutaWeb, $defaultUrl) {
    if (!empty($nombreBD) && file_exists($rutaFisica . $nombreBD)) {
        return $rutaWeb . $nombreBD;
    }
    return $defaultUrl;
}

// A) HERO BACKGROUND
$heroImage = validarImagen(
    $config['image_background'] ?? '', 
    $rutaFisicaBase, 
    $rutaWeb, 
    'https://images.unsplash.com/photo-1532106806998-abe7a5989beb?w=1600&q=80'
);

// B) CATEGORÍA MUJER
$imgMujer = validarImagen(
    $config['image_woman'] ?? '', 
    $rutaFisicaBase, 
    $rutaWeb, 
    'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=1200&q=80'
);

// C) CATEGORÍA HOMBRE
$imgHombre = validarImagen(
    $config['image_man'] ?? '', 
    $rutaFisicaBase, 
    $rutaWeb, 
    'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=800&q=80'
);

// D) CATEGORÍA SALE
$imgSale = validarImagen(
    $config['image_sale'] ?? '', 
    $rutaFisicaBase, 
    $rutaWeb, 
    'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800&q=80'
);

?>

<section class="hero">
    <img src="<?= htmlspecialchars($heroImage); ?>" alt="Hero" class="hero-image">

    <div class="hero-overlay"></div>
    <div class="hero-content">
        <p class="hero-label"><?= htmlspecialchars($heroLabel); ?></p>
        
        <h1><?= nl2br(htmlspecialchars($heroTitle)); ?></h1>
        
        <p>
            <?= nl2br(htmlspecialchars($heroSubtitle)); ?>
        </p>
        
        <a href="index.php?page=nueva-coleccion" class="btn btn-light">
            Explorar colección
        </a>
    </div>
</section>

<section class="categories">
    <div class="categories-inner">
        <div class="section-header">
            <p class="section-label"><?= nl2br(htmlspecialchars($catLabel)); ?></p>
            <h2 class="section-title"><?= nl2br(htmlspecialchars($catTitle)); ?></h2>
        </div>

        <div class="categories-grid">
            <a href="index.php?page=mujer" class="category-item category-large">
                <img src="<?= htmlspecialchars($imgMujer); ?>" alt="Mujer">
                <div class="category-overlay">
                    <h3>Colección Mujer</h3>
                    <span class="category-link">Ver todo →</span>
                </div>
            </a>

            <a href="index.php?page=hombre" class="category-item category-small">
                <img src="<?= htmlspecialchars($imgHombre); ?>" alt="Hombre">
                <div class="category-overlay">
                    <h3>Colección Hombre</h3>
                    <span class="category-link">Ver todo →</span>
                </div>
            </a>

            <a href="index.php?page=sale" class="category-item category-small">
                <img src="<?= htmlspecialchars($imgSale); ?>" alt="Sale">
                <div class="category-overlay">
                    <h3>Sale</h3>
                    <span class="category-link">Ver ofertas →</span>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="newsletter">
    <h2><?= nl2br(htmlspecialchars($newsTitle)); ?></h2>
    <p><?= nl2br(htmlspecialchars($newsSubtitle)); ?></p>

    <form class="newsletter-form" method="POST" action="index.php?page=subscribe">
        <input
            type="email"
            name="email"
            class="newsletter-input"
            placeholder="Tu correo electrónico"
            required
        >
        <button type="submit" class="btn-newsletter">
            Suscribirse
        </button>
    </form>
</section>