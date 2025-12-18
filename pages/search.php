<?php
// Obtener término de búsqueda
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8');

// Base de productos (simulada - después conectar a BD)
$todosLosProductos = [
    // Mujer
    ['id' => 1, 'nombre' => 'Vestido Midi de Seda', 'precio' => 245, 'marca' => 'Atelier',
     'categoria' => 'mujer', 'tags' => 'vestido seda elegante',
     'imagen' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=600&q=80'],
    ['id' => 2, 'nombre' => 'Blusa de Organza', 'precio' => 120, 'marca' => 'Premium',
     'categoria' => 'mujer', 'tags' => 'blusa organza elegante',
     'imagen' => 'https://images.unsplash.com/photo-1551163943-3f6a855d1153?w=600&q=80'],
    ['id' => 3, 'nombre' => 'Falda Plisada', 'precio' => 165, 'marca' => 'Essentials',
     'categoria' => 'mujer', 'tags' => 'falda plisada casual',
     'imagen' => 'https://images.unsplash.com/photo-1551163943-3f6a855d1153?w=600&q=80'],
    ['id' => 4, 'nombre' => 'Abrigo de Lana', 'precio' => 380, 'marca' => 'Winter',
     'categoria' => 'mujer', 'tags' => 'abrigo lana invierno',
     'imagen' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600&q=80'],
    // Hombre
    ['id' => 5, 'nombre' => 'Blazer Estructurado', 'precio' => 320, 'marca' => 'Premium Line',
     'categoria' => 'hombre', 'tags' => 'blazer formal elegante',
     'imagen' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80'],
    ['id' => 6, 'nombre' => 'Camisa Oxford', 'precio' => 95, 'marca' => 'Essentials',
     'categoria' => 'hombre', 'tags' => 'camisa oxford casual',
     'imagen' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&q=80'],
    ['id' => 7, 'nombre' => 'Pantalón Chino', 'precio' => 110, 'marca' => 'Atelier',
     'categoria' => 'hombre', 'tags' => 'pantalon chino casual',
     'imagen' => 'https://images.unsplash.com/photo-1624206112918-f140f087f9b5?w=600&q=80'],
    ['id' => 8, 'nombre' => 'Suéter de Cashmere', 'precio' => 295, 'marca' => 'Warm',
     'categoria' => 'hombre', 'tags' => 'sueter cashmere invierno',
     'imagen' => 'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=600&q=80'],
    // Sale
    ['id' => 9, 'nombre' => 'Blazer Clásico', 'precio' => 189, 'precio_anterior' => 280,
     'marca' => 'Premium', 'categoria' => 'sale', 'tags' => 'blazer clasico oferta',
     'imagen' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80'],
    ['id' => 10, 'nombre' => 'Vestido Floral', 'precio' => 120, 'precio_anterior' => 200,
     'marca' => 'Atelier', 'categoria' => 'sale', 'tags' => 'vestido floral oferta',
     'imagen' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=600&q=80'],
];

// Función de búsqueda
function buscarProductos($productos, $query) {
    if (empty($query)) return [];
    
    $query = mb_strtolower($query, 'UTF-8');
    $resultados = [];
    
    foreach ($productos as $producto) {
        $textoProducto = mb_strtolower(
            $producto['nombre'] . ' ' . 
            $producto['marca'] . ' ' . 
            $producto['categoria'] . ' ' . 
            $producto['tags'], 'UTF-8'
        );
        
        if (mb_strpos($textoProducto, $query) !== false) {
            $resultados[] = $producto;
        }
    }
    
    return $resultados;
}

$resultados = buscarProductos($todosLosProductos, $searchQuery);
$totalResultados = count($resultados);
?>

<section class="search-hero">
    <div class="search-hero-content">
        <h1>Resultados de búsqueda</h1>
        <?php if (!empty($searchQuery)): ?>
            <p>Resultados para: <strong>"<?php echo $searchQuery; ?>"</strong></p>
        <?php endif; ?>
    </div>
</section>

<section class="products search-results">
    <div class="products-container">
        <!-- Barra de búsqueda inline -->
        <div class="search-bar-inline">
            <form action="index.php" method="GET" class="inline-search-form">
                <input type="hidden" name="page" value="buscar">
                <input type="text" name="q" value="<?php echo $searchQuery; ?>" 
                       placeholder="Buscar productos..." class="inline-search-input">
                <button type="submit" class="inline-search-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </form>
        </div>

        <?php if (empty($searchQuery)): ?>
            <!-- Sin término de búsqueda -->
            <div class="search-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <h2>¿Qué estás buscando?</h2>
                <p>Ingresa un término de búsqueda para encontrar productos</p>
            </div>
            
        <?php elseif ($totalResultados === 0): ?>
            <!-- Sin resultados -->
            <div class="search-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 15s1.5-2 4-2 4 2 4 2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
                <h2>No encontramos resultados</h2>
                <p>No hay productos que coincidan con "<strong><?php echo $searchQuery; ?></strong>"</p>
                <div class="search-suggestions-inline">
                    <p>Prueba con:</p>
                    <a href="index.php?page=buscar&q=vestido">Vestidos</a>
                    <a href="index.php?page=buscar&q=blazer">Blazers</a>
                    <a href="index.php?page=buscar&q=camisa">Camisas</a>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Con resultados -->
            <div class="products-header">
                <p class="results-count"><?php echo $totalResultados; ?> producto<?php echo $totalResultados !== 1 ? 's' : ''; ?> encontrado<?php echo $totalResultados !== 1 ? 's' : ''; ?></p>
            </div>
            
            <div class="products-grid">
                <?php foreach ($resultados as $producto): ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="<?php echo $producto['imagen']; ?>" 
                             alt="<?php echo $producto['nombre']; ?>">
                        <?php if (isset($producto['precio_anterior'])): ?>
                            <span class="product-badge sale">SALE</span>
                        <?php endif; ?>
                        <button class="quick-add">Agregar al carrito</button>
                    </div>
                    <div class="product-info">
                        <p class="product-brand"><?php echo $producto['marca']; ?></p>
                        <p class="product-name"><?php echo $producto['nombre']; ?></p>
                        <p class="product-price">
                            $<?php echo $producto['precio']; ?>
                            <?php if (isset($producto['precio_anterior'])): ?>
                                <span class="old-price">$<?php echo $producto['precio_anterior']; ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>