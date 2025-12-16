<section class="page-hero">
    <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=1600&q=80" 
         alt="Mujer" class="page-hero-image">
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
        <h1>Colección Mujer</h1>
        <p>Elegancia y sofisticación en cada pieza</p>
    </div>
</section>

<section class="products">
    <div class="products-container">
        <div class="filter-sidebar">
            <div class="filter-group">
                <h4>Categoría</h4>
                <label><input type="checkbox"> Vestidos</label>
                <label><input type="checkbox"> Blusas</label>
                <label><input type="checkbox"> Pantalones</label>
                <label><input type="checkbox"> Faldas</label>
                <label><input type="checkbox"> Abrigos</label>
            </div>
            <div class="filter-group">
                <h4>Talla</h4>
                <label><input type="checkbox"> XS</label>
                <label><input type="checkbox"> S</label>
                <label><input type="checkbox"> M</label>
                <label><input type="checkbox"> L</label>
                <label><input type="checkbox"> XL</label>
            </div>
            <div class="filter-group">
                <h4>Precio</h4>
                <label><input type="checkbox"> $0 - $100</label>
                <label><input type="checkbox"> $100 - $200</label>
                <label><input type="checkbox"> $200 - $300</label>
                <label><input type="checkbox"> $300+</label>
            </div>
        </div>
        
        <div class="products-main">
            <div class="products-header">
                <p class="results-count">24 productos</p>
                <select class="sort-select">
                    <option>Ordenar por: Relevancia</option>
                    <option>Precio: menor a mayor</option>
                    <option>Precio: mayor a menor</option>
                    <option>Más recientes</option>
                </select>
            </div>
            
            <div class="products-grid">
                <?php
                $productosMujer = [
                    ['nombre' => 'Vestido Midi de Seda', 'precio' => 245, 'marca' => 'Atelier',
                     'imagen' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=600&q=80'],
                    ['nombre' => 'Blusa de Organza', 'precio' => 120, 'marca' => 'Premium',
                     'imagen' => 'https://images.unsplash.com/photo-1551163943-3f6a855d1153?w=600&q=80'],
                    ['nombre' => 'Falda Plisada', 'precio' => 165, 'marca' => 'Essentials',
                     'imagen' => 'https://images.unsplash.com/photo-1551163943-3f6a855d1153?w=600&q=80'],
                    ['nombre' => 'Abrigo de Lana', 'precio' => 380, 'marca' => 'Winter',
                     'imagen' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600&q=80'],
                ];
                
                foreach ($productosMujer as $producto): ?>
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
    </div>
</section>
