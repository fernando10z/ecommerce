        // 1. Feedback visual de escala para botones
        document.querySelectorAll('button[name^="btn_"]').forEach(btn => {
            btn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => this.style.transform = 'scale(1)', 150);
            });
        });

        // 2. Función global de confirmación (Adaptada para formularios con campos requeridos)
        function confirmarAccion(e, boton) {
            if (e) e.preventDefault();
            
            const accion = boton.getAttribute('title') || 'realizar esta acción';
            const name = boton.getAttribute('name');
            const form = boton.form || boton.closest('form');

            if (!form) {
                console.error("Error: El botón no pertenece a ningún formulario.");
                return;
            }

            // IMPORTANTE: Valida si los campos 'required' están llenos antes de abrir el modal
            if (!form.checkValidity()) {
                form.reportValidity();
                return; 
            }

            Swal.fire({
                title: `¿${accion}?`,
                text: "Esta acción modificará la base de datos de productos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // Color Emerald-500 de tu Tailwind
                cancelButtonColor: '#6b7280', 
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Feedback visual de carga
                    Swal.showLoading();

                    // Crear input oculto para que PHP detecte qué botón se pulsó
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = name;
                    hiddenInput.value = '1';
                    form.appendChild(hiddenInput);
                    
                    form.submit();
                }
            });
        }
        
        function toggleSaleFields() {
            const isChecked = document.getElementById('saleToggle').checked;
            document.getElementById('saleFields').classList.toggle('hidden', !isChecked);
        }

        function openProductModal(isNew = true) {
            if (isNew) {
                document.getElementById('productForm').reset();
                document.getElementById('edit_id').value = '';
                document.getElementById('modalTitle').innerText = 'Agregar Nuevo Producto';
                document.getElementById('submitBtn').name = 'btn_save_product';
                document.getElementById('saleFields').classList.add('hidden');
            }
            document.getElementById('productModal').classList.remove('hidden');
        }

        function closeProductModal() { document.getElementById('productModal').classList.add('hidden'); }

        function editProduct(data) {
            openProductModal(false);
            document.getElementById('modalTitle').innerText = 'Edit Product Details';
            document.getElementById('submitBtn').name = 'btn_update_product';
            
            document.getElementById('edit_id').value = data.id;
            document.querySelector('[name="prod_name"]').value = data.name;
            const skuInput = document.querySelector('[name="prod_sku"]');
            if (skuInput) {
                skuInput.value = data.sku || '';
            }
            document.querySelector('[name="prod_price"]').value = data.base_price;
            
            // ---> AÑADE ESTA LÍNEA PARA LA CATEGORÍA <---
            document.querySelector('[name="prod_category"]').value = data.category_id || '';
            
            document.querySelector('[name="prod_stock"]').value = data.stock_quantity;
            document.querySelector('[name="prod_low_stock"]').value = data.low_stock_threshold || 5;
            document.querySelector('[name="prod_status"]').value = data.status;
            document.querySelector('[name="prod_visibility"]').value = data.visibility || 'visible';
            document.querySelector('[name="prod_short_desc"]').value = data.short_description || '';
            const featuredInput = document.querySelector('[name="prod_featured"]');
            if (featuredInput) {
                featuredInput.checked = (data.is_featured == 1);
            }

            const hasSale = data.sale_price !== null && data.sale_price > 0;
            document.getElementById('saleToggle').checked = hasSale;
            document.querySelector('[name="prod_sale_price"]').value = data.sale_price || '';
            
            if(data.sale_start_at) document.querySelector('[name="sale_start_at"]').value = data.sale_start_at.replace(' ', 'T').substring(0, 16);
            if(data.sale_end_at) document.querySelector('[name="sale_end_at"]').value = data.sale_end_at.replace(' ', 'T').substring(0, 16);
            
            toggleSaleFields();
        }
    