        // 1. Feedback visual de escala
        document.querySelectorAll('.btn-action').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if(!this.getAttribute('onclick') || this.getAttribute('onclick').includes('return confirm')) {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => this.style.transform = 'scale(1)', 150);
                }
            });
        });

        // 2. Función global de confirmación
        function confirmarAccion(e, boton) {
            if (e) e.preventDefault();
            
            const accion = boton.getAttribute('title') || 'realizar esta acción';
            const name = boton.getAttribute('name');
            const form = boton.form;

            if (!form) {
                console.error("Error: El botón no pertenece a ningún formulario.");
                return;
            }

            Swal.fire({
                title: `¿${accion}?`,
                text: "Esta acción actualizará la sección Sale.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary)', 
                cancelButtonColor: '#6b7280', 
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.showLoading();
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = name;
                    hiddenInput.value = '1';
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });
        }