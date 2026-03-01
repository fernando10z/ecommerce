<?php
//CARGA DE RECURSOS DEL SISTEMA (ANTES DE CUALQUIER HTML)
require_once 'includes/auth-logic.php'; 
require_once 'includes/config.php';     
require_once __DIR__ . '/System/system-configuration/design-config.php';


// 1. OBTENER LA CONFIGURACIÓN ELEGIDA
$fontKey = $datos_actuales['font_style'];

// 2. BUSCAR LOS DATOS EN EL ARRAY (Si no existe, usa elegante por seguridad)
$selectedFont = $FONT_PRESETS[$fontKey] ?? $FONT_PRESETS['elegante'];

$currentPage = getCurrentPage();

// Verificar si la página existe
if (!array_key_exists($currentPage, $pages)) {
    $currentPage = 'home';
}

$pageTitle = $pages[$currentPage]['title'] . ' | ' . $SITE_NAME;
$pageFile = 'pages/' . $pages[$currentPage]['file'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo $selectedFont['url']; ?>">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        :root {
            /* Colores */
            --color-primary: <?php echo htmlspecialchars($PRIMARY_COLOR); ?>;
            --color-secondary: <?php echo htmlspecialchars($SECONDARY_COLOR); ?>;
            --color-tertiary: <?php echo htmlspecialchars($TERTIARY_COLOR); ?>;
            
            /* FUENTES DINÁMICAS DESDE EL ARRAY */
            --font-heading: <?php echo $selectedFont['heading']; ?>;
            --font-body: <?php echo $selectedFont['body']; ?>;
            
            /* Resto de variables */
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