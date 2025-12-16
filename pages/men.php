<section class="hero">
    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1600&q=80" 
         alt="Hero" class="hero-image">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <p class="hero-label">Primavera/Verano 2025</p>
        <h1>La elegancia<br>redefinida</h1>
        <p>Descubre nuestra nueva colección diseñada para la mujer 
           y el hombre contemporáneo.</p>
        <a href="index.php?page=nueva-coleccion" class="btn btn-light">
            Explorar colección
        </a>
    </div>
</section>

<!-- CATEGORIES -->
<section class="categories">
    <div class="section-header">
        <p class="section-label">Categorías</p>
        <h2 class="section-title">Explora por estilo</h2>
    </div>
    
    <div class="categories-grid">
        <a href="index.php?page=mujer" class="category-item category-large">
            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=1200&q=80" alt="Mujer">
            <div class="category-overlay">
                <h3>Colección Mujer</h3>
                <span class="category-link">Ver todo →</span>
            </div>
        </a>
        
        <a href="index.php?page=hombre" class="category-item category-small">
            <img src="https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=800&q=80" alt="Hombre">
            <div class="category-overlay">
                <h3>Colección Hombre</h3>
                <span class="category-link">Ver todo →</span>
            </div>
        </a>
        
        <a href="index.php?page=sale" class="category-item category-small">
            <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800&q=80" alt="Sale">
            <div class="category-overlay">
                <h3>Sale</h3>
                <span class="category-link">Ver ofertas →</span>
            </div>
        </a>
    </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter">
    <h2>Únete a nuestra comunidad</h2>
    <p>Recibe acceso anticipado a nuevas colecciones</p>
    <form class="newsletter-form" method="POST" action="index.php?page=subscribe">
        <input type="email" name="email" class="newsletter-input" 
               placeholder="Tu correo electrónico" required>
        <button type="submit" class="btn-newsletter">Suscribirse</button>
    </form>
</section>