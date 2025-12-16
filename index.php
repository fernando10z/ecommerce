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
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <?php include $pageFile; ?>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>