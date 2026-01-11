<?php
require_once 'includes/config.php';

$currentPage = getCurrentPage();

// Verificar si la página existe
if (!array_key_exists($currentPage, $pages)) {
    $currentPage = 'home';
}

$pageTitle = $pages[$currentPage]['title'] . ' | ' . SITE_NAME;
$pageFile = 'pages/' . $pages[$currentPage]['file'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        :root {
            --color-primary: #0a0a0a;
            --color-secondary: #f8f7f4;
            --color-accent: #b89968;
            --color-text: #1a1a1a;
            --color-text-light: #757575;
            --color-border: #e5e5e5;
            --color-white: #ffffff;
            --color-sale: #c23a3a;
            
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Work Sans', sans-serif;
            
            --spacing: 8px;
            --transition: 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            --header-height: 96px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <?php include $pageFile; ?>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>