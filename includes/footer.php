<footer>
    <div class="footer-content">
        <div class="footer-brand">
            <h3><?php echo SITE_NAME; ?></h3>
            <p>Redefiniendo la elegancia contemporánea desde 2020. 
               Cada pieza es diseñada pensando en la atemporalidad.</p>
        </div>
        
        <div class="footer-section">
            <h4>Comprar</h4>
            <ul>
                <?php foreach ($pages as $slug => $page): ?>
                    <?php if ($page['nav']): ?>
                    <li><a href="index.php?page=<?php echo $slug; ?>">
                        <?php echo $page['title']; ?>
                    </a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="footer-section">
            <h4>Ayuda</h4>
            <ul>
                <li><a href="#">Atención al Cliente</a></li>
                <li><a href="#">Envíos y Devoluciones</a></li>
                <li><a href="#">Guía de Tallas</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h4>Compañía</h4>
            <ul>
                <li><a href="#">Sobre Nosotros</a></li>
                <li><a href="#">Sostenibilidad</a></li>
                <li><a href="#">Tiendas</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>© <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. 
           Todos los derechos reservados.</p>
        <div class="payment-methods">
            <span>Visa</span>
            <span>Mastercard</span>
            <span>PayPal</span>
        </div>
    </div>
</footer>

<script src="assets/js/main.js"></script>