<section class="page-hero">
    <img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600&q=80" 
         alt="Nueva Colección" class="page-hero-image">
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
        <p class="hero-label">Primavera/Verano 2025</p>
        <h1>Nueva Colección</h1>
        <p>Piezas únicas para una nueva temporada</p>
    </div>
</section>

<section class="products">
    <div class="products-container">
        <div class="products-header">
            <div>
                <p class="section-label">Nuevas llegadas</p>
                <h2 class="section-title">Lo más reciente</h2>
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active">Todo</button>
                <button class="filter-tab">Mujer</button>
                <button class="filter-tab">Hombre</button>
            </div>
        </div>
        
        <div class="products-grid">
            <?php
            // Productos de nueva colección (simulados)
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
