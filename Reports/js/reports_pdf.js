// javascript/reports_pdf.js

function descargarPDF(nombreArchivoGenerado) {
    // Se deshabilita el botón temporalmente para evitar múltiples clics
    const boton = document.getElementById('btnDescargar');
    if (!boton) return;
    
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF...';

    // Se selecciona el área exacta que se desea convertir a PDF
    const elemento = document.getElementById('documento-pdf');

    // Configuración del formato del PDF
    const opciones = {
        margin:       10, 
        // Se utiliza la variable recibida como parámetro para el nombre
        filename:     nombreArchivoGenerado,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };

    // Ejecución de la librería html2pdf
    html2pdf().set(opciones).from(elemento).save().then(() => {
        // Se restaura el botón tras la descarga
        boton.disabled = false;
        boton.innerHTML = '<i class="fas fa-file-download"></i> Descargar PDF';
    });
}