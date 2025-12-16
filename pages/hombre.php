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
            $productosHombre = [
                ['nombre' => 'Blazer Estructurado', 'precio' => 320, 'marca' => 'Premium Line',
                 'imagen' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80'],
                ['nombre' => 'Camisa Oxford', 'precio' => 95, 'marca' => 'Essentials',
                 'imagen' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&q=80'],
                ['nombre' => 'Pantalón Chino', 'precio' => 110, 'marca' => 'Atelier',
                 'imagen' => 'https://images.unsplash.com/photo-1624206112918-f140f087f9b5?w=600&q=80'],
                ['nombre' => 'Suéter de Cashmere', 'precio' => 295, 'marca' => 'Warm',
                 'imagen' => 'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=600&q=80'],
            ];
            
            foreach ($productosHombre as $producto): ?>
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="<?php echo $producto['imagen']; ?>" 
                         alt="<?php echo $producto['nombre']; ?>">
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
