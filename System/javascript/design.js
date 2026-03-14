        // Configuración compartida de SweetAlert (Estética unificada)
        const swalEstilo = {
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981', // Tu color verde/esmeralda
            cancelButtonColor: '#6b7280', // Gris para cancelar
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            focusCancel: true
        };

        // 1. EFECTO VISUAL (Animación de pulsación para todos los botones)
        document.querySelectorAll('.btn-action').forEach(btn => {
            btn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => this.style.transform = 'scale(1)', 150);
            });
        });

        // 2. FUNCIÓN PARA BOTONES PHP (Guardar, Eliminar, Resetear)
        // Esta es la que usan tus otros botones. Es compatible 100%.
        function confirmarAccion(e, boton) {
            if (e) e.preventDefault(); // Detiene el envío automático
            
            // Obtiene datos del botón automáticamente
            const accion = boton.getAttribute('title') || 'realizar acción';
            const name = boton.getAttribute('name'); // Vital para el PHP
            const form = boton.form;

            if (!form) return; // Seguridad si no hay formulario

            Swal.fire({
                ...swalEstilo,
                title: `¿${accion}?`,
                text: "Esta acción actualizará la configuración."
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.showLoading(); // Feedback visual de carga

                    // TRUCO VITAL: Crear input oculto para que PHP detecte el botón
                    if (name) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = name;
                        input.value = '1';
                        form.appendChild(input);
                    }
                    
                    form.submit(); // Envío manual
                }
            });
        }

        // 3. FUNCIÓN EXCLUSIVA PARA EL OJO (Solo visual, no envía form)
        function toggleBanner(e) {
    if (e) e.preventDefault();

    Swal.fire({
        ...swalEstilo,
        title: '¿Cambiar visibilidad?',
        text: 'El banner se mostrará u ocultará en la web inmediatamente.'
    }).then((result) => {
        if (result.isConfirmed) {
            const chk = document.getElementById('chk_banner_visible');
            const form = document.getElementById('configForm');

            // 1. Cambiamos el estado del checkbox
            chk.checked = !chk.checked; 

            // 2. Creamos el input oculto para que PHP detecte que pulsamos "btn_save_banner"
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'btn_save_banner';
            input.value = '1';
            form.appendChild(input);

            // 3. Enviamos el formulario
            form.submit();
        }
    });
}