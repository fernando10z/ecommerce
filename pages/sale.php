<section class="page-hero sale-hero">
    <div class="sale-banner">
        <h1>SALE</h1>
        <p class="sale-discount">Hasta 50% OFF</p>
        <p>En piezas seleccionadas de temporadas anteriores</p>
    </div>
</section>

<section class="products">
    <div class="products-container">
        <div class="products-grid">
            <?php
            $productosSale = [
                ['nombre' => 'Blazer Clásico', 'precio' => 189, 'precio_anterior' => 280,
                 'marca' => 'Premium', 'descuento' => '30%',
                 'imagen' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80'],
                ['nombre' => 'Vestido Floral', 'precio' => 120, 'precio_anterior' => 200,
                 'marca' => 'Atelier', 'descuento' => '40%',
                 'imagen' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=600&q=80'],
                ['nombre' => 'Suéter Oversize', 'precio' => 150, 'precio_anterior' => 295,
                 'marca' => 'Warm', 'descuento' => '50%',
                 'imagen' => 'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=600&q=80'],
                ['nombre' => 'Bolso de Cuero', 'precio' => 280, 'precio_anterior' => 450,
                 'marca' => 'Leather Co.', 'descuento' => '38%',
                 'imagen' => 'https://images.unsplash.com/photo-1591369822096-ffd140ec948f?w=600&q=80'],
            ];
            
            foreach ($productosSale as $producto): ?>
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="<?php echo $producto['imagen']; ?>" 
                         alt="<?php echo $producto['nombre']; ?>">
                    <span class="product-badge sale">-<?php echo $producto['descuento']; ?></span>
                    <button class="quick-add">Agregar al carrito</button>
                </div>
                <div class="product-info">
                    <p class="product-brand"><?php echo $producto['marca']; ?></p>
                    <p class="product-name"><?php echo $producto['nombre']; ?></p>
                    <p class="product-price">
                        $<?php echo $producto['precio']; ?>
                        <span class="old-price">$<?php echo $producto['precio_anterior']; ?></span>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>