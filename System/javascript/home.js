        // 1. Feedback visual de escala para botones
        document.querySelectorAll('.btn-action').forEach(btn => {
            btn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => this.style.transform = 'scale(1)', 150);
            });
        });

        // 2. Función global de confirmación (Misma lógica que design-system)
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
                text: "Esta acción actualizará la sección visual del Home.",
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
                    // Feedback visual de carga
                    Swal.showLoading();

                    // IMPORTANTE: Crear input oculto para que PHP detecte qué botón se pulsó
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = name;
                    hiddenInput.value = '1';
                    form.appendChild(hiddenInput);
                    
                    form.submit();
                }
            });
        }
   