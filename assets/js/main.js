document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // MOBILE MENU
    // ==========================================
    
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navCenter = document.querySelector('.nav-center');
    const navRight = document.querySelector('.nav-right');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            
            // Crear menú móvil si no existe
            let mobileMenu = document.querySelector('.mobile-menu');
            
            if (!mobileMenu) {
                mobileMenu = document.createElement('div');
                mobileMenu.className = 'mobile-menu';
                mobileMenu.innerHTML = `
                    <div class="mobile-menu-content">
                        ${navCenter ? navCenter.innerHTML : ''}
                        <div class="mobile-menu-icons">
                            <a href="#">Buscar</a>
                            <a href="#">Mi Cuenta</a>
                            <a href="#">Carrito (<span class="mobile-cart-count">0</span>)</a>
                        </div>
                    </div>
                `;
                document.body.appendChild(mobileMenu);
                
                // Agregar estilos dinámicos para el menú móvil
                const style = document.createElement('style');
                style.textContent = `
                    .mobile-menu {
                        position: fixed;
                        top: var(--header-height, 96px);
                        left: 0;
                        width: 100%;
                        height: calc(100vh - var(--header-height, 96px));
                        background: var(--color-white, #fff);
                        z-index: 999;
                        transform: translateX(-100%);
                        transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1);
                        overflow-y: auto;
                    }
                    .mobile-menu.active {
                        transform: translateX(0);
                    }
                    .mobile-menu-content {
                        padding: 40px 24px;
                    }
                    .mobile-menu ul {
                        list-style: none;
                        padding: 0;
                        margin: 0;
                    }
                    .mobile-menu li {
                        margin-bottom: 24px;
                    }
                    .mobile-menu a {
                        font-size: 1.5rem;
                        color: #1a1a1a;
                        text-decoration: none;
                        font-weight: 400;
                        letter-spacing: 0.02em;
                        transition: color 0.3s;
                    }
                    .mobile-menu a:hover,
                    .mobile-menu a.active {
                        color: #b89968;
                    }
                    .mobile-menu-icons {
                        margin-top: 40px;
                        padding-top: 40px;
                        border-top: 1px solid #e5e5e5;
                    }
                    .mobile-menu-icons a {
                        display: block;
                        font-size: 1rem;
                        margin-bottom: 16px;
                    }
                    .mobile-toggle.active span:nth-child(1) {
                        transform: rotate(45deg) translate(5px, 5px);
                    }
                    .mobile-toggle.active span:nth-child(2) {
                        opacity: 0;
                    }
                    .mobile-toggle.active span:nth-child(3) {
                        transform: rotate(-45deg) translate(5px, -5px);
                    }
                `;
                document.head.appendChild(style);
            }
            
            mobileMenu.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
        });
    }
    
    
    // ==========================================
    // HEADER SCROLL EFFECT
    // ==========================================
    
    const header = document.querySelector('header');
    let lastScroll = 0;
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        // Añadir sombra al hacer scroll
        if (currentScroll > 50) {
            header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.08)';
        } else {
            header.style.boxShadow = 'none';
        }
        
        // Ocultar/mostrar header al hacer scroll (opcional)
        // if (currentScroll > lastScroll && currentScroll > 200) {
        //     header.style.transform = 'translateY(-100%)';
        // } else {
        //     header.style.transform = 'translateY(0)';
        // }
        
        lastScroll = currentScroll;
    });
    
    
    // ==========================================
    // FILTER TABS
    // ==========================================
    
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remover active de todos
            filterTabs.forEach(t => t.classList.remove('active'));
            // Añadir active al clickeado
            this.classList.add('active');
            
            // Aquí puedes agregar lógica para filtrar productos
            const filter = this.textContent.trim().toLowerCase();
            filterProducts(filter);
        });
    });
    
    function filterProducts(filter) {
        const products = document.querySelectorAll('.product-card');
        
        products.forEach(product => {
            // Animación de fade
            product.style.opacity = '0';
            product.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                // Aquí iría la lógica real de filtrado
                // Por ahora solo muestra todos
                product.style.opacity = '1';
                product.style.transform = 'translateY(0)';
            }, 300);
        });
    }
    
    
    // ==========================================
    // CART FUNCTIONALITY
    // ==========================================
    
    let cartCount = parseInt(localStorage.getItem('cartCount')) || 0;
    const cartBadge = document.querySelector('.cart-badge');
    
    // Actualizar badge inicial
    if (cartBadge) {
        cartBadge.textContent = cartCount;
        if (cartCount === 0) {
            cartBadge.style.display = 'none';
        }
    }
    
    // Quick Add buttons
    const quickAddBtns = document.querySelectorAll('.quick-add');
    
    quickAddBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Incrementar contador
            cartCount++;
            localStorage.setItem('cartCount', cartCount);
            
            // Actualizar badge
            if (cartBadge) {
                cartBadge.textContent = cartCount;
                cartBadge.style.display = 'flex';
                
                // Animación del badge
                cartBadge.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    cartBadge.style.transform = 'scale(1)';
                }, 200);
            }
            
            // Cambiar texto del botón
            const originalText = this.textContent;
            this.textContent = '✓ Agregado';
            this.style.background = '#b89968';
            this.style.color = '#fff';
            this.style.borderColor = '#b89968';
            
            // Restaurar después de 2 segundos
            setTimeout(() => {
                this.textContent = originalText;
                this.style.background = '';
                this.style.color = '';
                this.style.borderColor = '';
            }, 2000);
            
            // Mostrar notificación
            showNotification('Producto agregado al carrito');
        });
    });
    
    
    // ==========================================
    // NOTIFICATION SYSTEM
    // ==========================================
    
    function showNotification(message, type = 'success') {
        // Remover notificación existente
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Crear notificación
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <span>${message}</span>
            <button class="notification-close">×</button>
        `;
        
        // Estilos
        const style = document.createElement('style');
        style.textContent = `
            .notification {
                position: fixed;
                bottom: 30px;
                right: 30px;
                background: #0a0a0a;
                color: #fff;
                padding: 16px 24px;
                border-radius: 4px;
                display: flex;
                align-items: center;
                gap: 16px;
                z-index: 10000;
                animation: slideIn 0.4s cubic-bezier(0.23, 1, 0.32, 1);
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            }
            .notification-success {
                border-left: 3px solid #b89968;
            }
            .notification-error {
                border-left: 3px solid #c23a3a;
            }
            .notification-close {
                background: none;
                border: none;
                color: #fff;
                font-size: 1.5rem;
                cursor: pointer;
                opacity: 0.7;
                transition: opacity 0.3s;
            }
            .notification-close:hover {
                opacity: 1;
            }
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(notification);
        
        // Cerrar al hacer click
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.style.animation = 'slideOut 0.3s forwards';
            setTimeout(() => notification.remove(), 300);
        });
        
        // Auto cerrar después de 3 segundos
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.animation = 'slideOut 0.3s forwards';
                setTimeout(() => notification.remove(), 300);
            }
        }, 3000);
    }
    
    
    // ==========================================
    // SMOOTH SCROLL
    // ==========================================
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    
    // ==========================================
    // NEWSLETTER FORM
    // ==========================================
    
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('.newsletter-input');
            const email = emailInput.value.trim();
            
            if (validateEmail(email)) {
                // Aquí enviarías el email al servidor
                showNotification('¡Gracias por suscribirte!', 'success');
                emailInput.value = '';
            } else {
                showNotification('Por favor ingresa un email válido', 'error');
            }
        });
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    
    // ==========================================
    // PRODUCT CARD HOVER (Touch devices)
    // ==========================================
    
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        card.addEventListener('touchstart', function() {
            // En dispositivos táctiles, mostrar el botón quick-add
            const quickAdd = this.querySelector('.quick-add');
            if (quickAdd) {
                quickAdd.style.opacity = '1';
                quickAdd.style.transform = 'translateX(-50%) translateY(0)';
            }
        });
    });
    
    
    // ==========================================
    // LAZY LOADING IMAGES
    // ==========================================
    
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px'
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback para navegadores sin IntersectionObserver
        images.forEach(img => {
            img.src = img.dataset.src;
        });
    }
    
    // ==========================================
    // ANIMATION ON SCROLL
    // ==========================================
    
    const animateElements = document.querySelectorAll('.product-card, .category-item');
    
    if ('IntersectionObserver' in window) {
        const animateObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });
        
        animateElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            animateObserver.observe(el);
        });
    }
    
});


// ==========================================
// SEARCH MODAL FUNCTIONALITY
// ==========================================

const searchToggle = document.getElementById('searchToggle');
const searchModal = document.getElementById('searchModal');
const searchClose = document.getElementById('searchClose');
const searchInput = document.querySelector('.search-modal .search-input');

if (searchToggle && searchModal) {
    // Abrir modal
    searchToggle.addEventListener('click', function(e) {
        e.preventDefault();
        searchModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Focus en input después de animación
        setTimeout(() => {
            if (searchInput) searchInput.focus();
        }, 300);
    });
    
    // Cerrar con botón X
    if (searchClose) {
        searchClose.addEventListener('click', function() {
            closeSearchModal();
        });
    }
    
    // Cerrar con click en overlay
    const searchOverlay = document.querySelector('.search-modal-overlay');
    if (searchOverlay) {
        searchOverlay.addEventListener('click', function() {
            closeSearchModal();
        });
    }
    
    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchModal.classList.contains('active')) {
            closeSearchModal();
        }
    });
}

function closeSearchModal() {
    if (searchModal) {
        searchModal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Prevenir envío de formulario vacío
const searchForm = document.querySelector('.search-modal .search-form');
if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
        const input = this.querySelector('.search-input');
        if (input && input.value.trim() === '') {
            e.preventDefault();
            input.focus();
        }
    });
}


// ==========================================
// UTILITY FUNCTIONS (Global)
// ==========================================

// Formatear precio
function formatPrice(price) {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}
