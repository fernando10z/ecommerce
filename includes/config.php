<?php
// Configuración del sitio
define('SITE_NAME', 'ATELIER');
define('SITE_URL', 'http://localhost/ecommerce');

// Configuración de la base de datos (para futuro)
define('DB_HOST', 'mysql');
define('DB_NAME', 'ecommerce');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// Páginas disponibles
$pages = [
    'home' => [
        'title' => 'Inicio',
        'file' => 'home.php',
        'nav' => false
    ],
    'nueva-coleccion' => [
        'title' => 'Nueva Colección',
        'file' => 'new-collection.php',
        'nav' => true
    ],
    'mujer' => [
        'title' => 'Mujer',
        'file' => 'woman.php',
        'nav' => true
    ],
    'hombre' => [
        'title' => 'Hombre',
        'file' => 'men.php',
        'nav' => true
    ],
    'sale' => [
        'title' => 'Sale',
        'file' => 'sale.php',
        'nav' => true
    ]
];

// Función para obtener página actual
function getCurrentPage() {
    return isset($_GET['page']) ? $_GET['page'] : 'home';
}

// Función para verificar si es página activa
function isActivePage($page) {
    return getCurrentPage() === $page ? 'active' : '';
}
?>