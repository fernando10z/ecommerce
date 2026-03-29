<?php
// Reports/products_reports.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['datos_reporte'])) {
    die('Acceso denegado o no se recibieron datos para generar el reporte.');
}

$datos_tabla = json_decode($_POST['datos_reporte'], true);

if (!is_array($datos_tabla)) {
    die('El formato de los datos recibidos es inválido.');
}

$timezone = new DateTimeZone('America/Lima');
$current_date = new DateTime('now', $timezone);
$fecha_reporte = $current_date->format('d/m/Y H:i:s');
$nombre_archivo = 'Reporte_Inventario_' . $current_date->format('Ymd_His') . '.pdf';

// Cálculos generales para la cabecera
$total_productos = count($datos_tabla);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        body { 
            font-family: Arial, sans-serif; /* Fuente más tradicional para reportes */
            margin: 20px; 
            color: #000; 
            background-color: #f3f4f6;
        }
        .contenedor-principal { 
            max-width: 1000px; 
            margin: 0 auto; 
            background-color: white; 
            padding: 40px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
        }
        
        /* Botón de descarga */
        .controles { max-width: 1000px; margin: 0 auto 20px auto; text-align: right; }
        .btn-descargar { background-color: #10b981; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold; transition: background-color 0.3s; }
        .btn-descargar:hover { background-color: #059669; }

        /* Estilos de la tabla estilo reporte corporativo */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 11px; 
        }
        th, td { 
            border: 1px solid #000; /* Bordes sólidos tipo Excel */
            padding: 6px 8px; 
        }
        
        /* Cabecera principal azul */
        .header-principal th { 
            background-color: #3a7bd5; 
            color: white; 
            font-size: 14px; 
            text-align: left; 
            padding: 12px;
            text-transform: uppercase;
        }

        /* Sección de Metadatos (sin bordes visibles internos) */
        .meta-row th, .meta-row td {
            border: none;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            padding: 4px 8px;
        }
        .meta-row th { text-align: left; width: 20%; background-color: transparent; }
        .meta-row td { text-align: left; width: 80%; }
        .meta-row.first th, .meta-row.first td { border-top: 1px solid #000; padding-top: 8px;}
        .meta-row.last th, .meta-row.last td { border-bottom: 1px solid #000; padding-bottom: 8px;}

        /* Encabezados de columnas de datos */
        .col-headers th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
            text-align: center; 
        }

        /* Alineación de datos */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        /* Colores de estado (Adaptados a tu inventario) */
        .bg-activo { background-color: #28a745; color: white; text-align: center; font-weight: bold;}
        .bg-inactivo { background-color: #dc3545; color: white; text-align: center; font-weight: bold;}
        .bg-eliminado { background-color: #6c757d; color: white; text-align: center; font-weight: bold;}
        .bg-default { background-color: #f8f9fa; color: #000; text-align: center; }
    </style>
</head>
<body>
    <div class="controles">
        <button id="btnDescargar" class="btn-descargar" onclick="descargarPDF('<?php echo $nombre_archivo; ?>')">
            <i class="fas fa-file-download"></i> Descargar PDF
        </button>
    </div>

    <div id="documento-pdf" class="contenedor-principal">
        <table>
            <tr class="header-principal">
                <th colspan="8">REPORTE DE INVENTARIO - TECNOHUB</th>
            </tr>
            
            <tr class="meta-row first">
                <th>Empresa:</th>
                <td colspan="7">TecnoHUB</td>
            </tr>
            <tr class="meta-row">
                <th>Fecha de Generación:</th>
                <td colspan="7"><?php echo htmlspecialchars($fecha_reporte); ?></td>
            </tr>
            <tr class="meta-row">
                <th>Usuario Responsable:</th>
                <td colspan="7">Administrador del Sistema</td>
            </tr>
            <tr class="meta-row last">
                <th>Total de Registros:</th>
                <td colspan="7"><?php echo $total_productos; ?> productos en pantalla</td>
            </tr>

            <tr>
                <th colspan="8" style="background-color: #f2f2f2; height: 10px; padding: 0;"></th>
            </tr>

            <tr class="col-headers">
                <th style="width: 3%;">#</th>
                <th style="width: 30%;">Detalles del Producto</th>
                <th style="width: 12%;">Categoría</th>
                <th style="width: 10%;">Stock</th>
                <th style="width: 12%;">Precio Base</th>
                <th style="width: 12%;">Oferta</th>
                <th style="width: 11%;">Estado</th>
                <th style="width: 10%;">Visibilidad</th>
            </tr>

            <?php 
            $contador = 1;
            foreach ($datos_tabla as $fila): 
                // Determinamos el color de fondo para la celda de estado
                $estado_texto = trim($fila['estado']);
                $clase_estado = 'bg-default';
                
                if (stripos($estado_texto, 'Activo') !== false) {
                    $clase_estado = 'bg-activo';
                } elseif (stripos($estado_texto, 'Inactivo') !== false || stripos($estado_texto, 'Draft') !== false) {
                    $clase_estado = 'bg-inactivo';
                } elseif (stripos($estado_texto, 'Eliminado') !== false) {
                    $clase_estado = 'bg-eliminado';
                }
            ?>
                <tr>
                    <td class="text-center"><?php echo $contador++; ?></td>
                    <td class="text-left fw-bold"><?php echo htmlspecialchars($fila['detalle']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($fila['categoria']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($fila['stock']); ?></td>
                    <td class="text-right"><?php echo htmlspecialchars($fila['precio_base']); ?></td>
                    <td class="text-right fw-bold" style="color: #059669;"><?php echo htmlspecialchars($fila['oferta']); ?></td>
                    <td class="<?php echo $clase_estado; ?>"><?php echo htmlspecialchars($estado_texto); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($fila['visibilidad']); ?></td>
                </tr>
            <?php endforeach; ?>
            
            <tr>
                <th colspan="8" style="background-color: #f2f2f2; height: 5px; padding: 0;"></th>
            </tr>
        </table>
    </div> 

    <script>
        function descargarPDF(nombreArchivo) {
            const elemento = document.getElementById('documento-pdf');
            const boton = document.getElementById('btnDescargar');
            
            // Ocultamos el botón temporalmente para que no cambie el foco de la página
            boton.style.opacity = '0.5';
            boton.innerText = 'Generando...';

            const opciones = {
                margin:       10,
                filename:     nombreArchivo,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' } // Landscape para que quepan las 8 columnas
            };

            html2pdf().set(opciones).from(elemento).save().then(() => {
                boton.style.opacity = '1';
                boton.innerHTML = '<i class="fas fa-file-download"></i> Descargar PDF';
            });
        }
    </script>
</body>
</html>