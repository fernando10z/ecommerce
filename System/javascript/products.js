        // 1. Feedback visual de escala para botones
        document.querySelectorAll('button[name^="btn_"]').forEach(btn => {
            btn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => this.style.transform = 'scale(1)', 150);
            });
        });

        // 2. Función global de confirmación (Adaptada con validación estricta de ofertas)
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

            // --- INICIO DE VALIDACIÓN ESTRICTA DE OFERTAS ---
            // Solo validamos si el switch de oferta está activado y es el formulario de productos
            const saleToggle = document.getElementById('saleToggle');
            if (saleToggle && saleToggle.checked && form.id === 'productForm') {
                const basePrice = parseFloat(form.querySelector('[name="prod_price"]').value) || 0;
                const salePrice = parseFloat(form.querySelector('[name="prod_sale_price"]').value) || 0;
                const startDate = form.querySelector('[name="sale_start_at"]').value;
                const endDate = form.querySelector('[name="sale_end_at"]').value;

                // Bloquea si la oferta es mayor o igual al precio original
                if (salePrice >= basePrice && basePrice > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Precio Inválido',
                        text: 'El precio de oferta no puede ser mayor o igual al precio base.',
                        confirmButtonColor: '#10b981'
                    });
                    return; // Detiene el envío del formulario al instante
                }

                // Bloquea si la fecha de fin es anterior a la fecha de inicio
                if (startDate && endDate && endDate < startDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fechas Inválidas',
                        text: 'La fecha de fin no puede ser anterior a la fecha de inicio.',
                        confirmButtonColor: '#10b981'
                    });
                    return; // Detiene el envío del formulario al instante
                }
            }
            // --- FIN DE VALIDACIÓN ESTRICTA ---

            Swal.fire({
                title: `¿${accion}?`,
                text: "Esta acción modificará la base de datos de productos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981', 
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
            
            if(data.sale_start_at) document.querySelector('[name="sale_start_at"]').value = data.sale_start_at.substring(0, 10);
            if(data.sale_end_at) document.querySelector('[name="sale_end_at"]').value = data.sale_end_at.substring(0, 10);
            
            toggleSaleFields();
        }
        
        // Función principal para confirmar y disparar la recolección de datos
        function confirmarGeneracionReporte() {
            Swal.fire({
                title: '¿Generar reporte de inventario?',
                text: 'Se capturará la información que se visualiza actualmente en la tabla.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                confirmButtonText: '<i class="fas fa-check"></i> Sí, generar',
                cancelButtonText: '<i class="fas fa-times"></i> Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarDatosReporte();
                }
            });
        }

        // Función que extrae los datos del DOM y los envía por POST
        function enviarDatosReporte() {
            // Seleccionar todas las filas del cuerpo de la tabla
            const filas = document.querySelectorAll('.data-table tbody tr');
            const datosTabla = [];

            filas.forEach(fila => {

                // // Si la fila fue ocultada por los filtros, la saltamos para que no vaya al PDF
                if (fila.style.display === 'none') return;

                const celdas = fila.querySelectorAll('td');
                
                // Asegurarse de que la fila tenga celdas (ignorando encabezados si los hubiera)
                if (celdas.length > 0) {
                    const producto = {
                        // Se reemplazan los saltos de línea por un guion para que el SKU y el Nombre queden en una sola línea
                        detalle: celdas[0].innerText.replace(/\n/g, ' - ').trim(),
                        categoria: celdas[1].innerText.trim(),
                        // Se limpia el texto complejo de la barra de progreso de stock
                        stock: celdas[2].innerText.replace(/\n/g, ' | ').trim(),
                        precio_base: celdas[3].innerText.trim(),
                        oferta: celdas[4].innerText.trim(),
                        estado: celdas[5].innerText.trim(),
                        visibilidad: celdas[6].innerText.trim()
                        // Se ignora la celda[7] porque corresponde a los botones de Acción
                    };
                    datosTabla.push(producto);
                }
            });

            if (datosTabla.length === 0) {
                Swal.fire('Error', 'No hay datos en la tabla para exportar.', 'error');
                return;
            }

            // Creación de un formulario dinámico e invisible
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../Reports/products_reports.php'; 
            form.target = '_blank'; // Abre el reporte en una nueva pestaña

            // Creación de un input oculto que contendrá el JSON con la información
            const inputData = document.createElement('input');
            inputData.type = 'hidden';
            inputData.name = 'datos_reporte';
            inputData.value = JSON.stringify(datosTabla);

            // Añadir el input al formulario, el formulario al documento, enviarlo y luego destruirlo
            form.appendChild(inputData);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
        // Validación en tiempo real del precio de oferta vs precio base
        document.addEventListener('DOMContentLoaded', () => {
            const salePriceInput = document.querySelector('[name="prod_sale_price"]');
            const basePriceInput = document.querySelector('[name="prod_price"]');

            if (salePriceInput && basePriceInput) {
                salePriceInput.addEventListener('change', function() {
                    let basePrice = parseFloat(basePriceInput.value) || 0;
                    let salePrice = parseFloat(this.value) || 0;

                    // Si el precio de oferta es mayor o igual al base, y el base ya fue ingresado
                    if (salePrice >= basePrice && basePrice > 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Precio inválido',
                            text: 'El precio de oferta no puede ser mayor o igual al precio original ($' + basePrice + ').',
                            confirmButtonColor: '#d97706' // Color amber-600 para combinar con la sección de oferta
                        });
                        this.value = ''; // Borra el valor incorrecto automáticamente
                    }
                });
            }
        });
        // Validación de Fechas: Evitar que la fecha de fin sea anterior a la de inicio
        document.addEventListener('DOMContentLoaded', () => {
            const startDateInput = document.querySelector('[name="sale_start_at"]');
            const endDateInput = document.querySelector('[name="sale_end_at"]');

            if (startDateInput && endDateInput) {
                // 1. Al cambiar la fecha de inicio, actualizamos el mínimo permitido en la fecha de fin
                startDateInput.addEventListener('change', function() {
                    endDateInput.min = this.value;

                    // Si ya había una fecha de fin escrita y quedó en el pasado respecto al nuevo inicio, la borramos
                    if (endDateInput.value && endDateInput.value < this.value) {
                        endDateInput.value = '';
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fecha inválida',
                            text: 'La fecha de fin de la oferta no puede ser anterior a la fecha de inicio.',
                            confirmButtonColor: '#d97706'
                        });
                    }
                });

                // 2. Validación extra por si intentan tipear directamente en el campo de fin
                endDateInput.addEventListener('change', function() {
                    if (startDateInput.value && this.value < startDateInput.value) {
                        this.value = '';
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fecha inválida',
                            text: 'La fecha de fin de la oferta no puede ser anterior a la fecha de inicio.',
                            confirmButtonColor: '#d97706'
                        });
                    }
                });
            }
        });
        // Bloquear la letra 'e', 'E', '+' y '-' en todos los campos numéricos
        document.addEventListener('DOMContentLoaded', () => {
            // Seleccionamos todos los inputs que sean de tipo número
            const numberInputs = document.querySelectorAll('input[type="number"]');
            
            numberInputs.forEach(input => {
                input.addEventListener('keydown', function(evento) {
                    // Lista de teclas que queremos prohibir
                    const teclasBloqueadas = ['e', 'E', '+', '-'];
                    
                    if (teclasBloqueadas.includes(evento.key)) {
                        evento.preventDefault(); // Detiene la acción de escribir la letra
                    }
                });
            });
        });

        // ==========================================
        // MÓDULO: Filtros de Inventario
        // ==========================================
        const ProductFilters = {
            // 1. Inicializar elementos
            init: function() {
                this.searchInput = document.getElementById('filter_search');
                this.categorySelect = document.getElementById('filter_category');
                this.statusSelect = document.getElementById('filter_status');
                this.productRows = document.querySelectorAll('.product-row');

                // Si existen los filtros en la página, activamos los eventos
                if (this.searchInput && this.categorySelect && this.statusSelect) {
                    this.bindEvents();
                }
            },

            // 2. Asignar eventos a los inputs
            bindEvents: function() {
                this.searchInput.addEventListener('keyup', () => this.filterTable());
                this.categorySelect.addEventListener('change', () => this.filterTable());
                this.statusSelect.addEventListener('change', () => this.filterTable());
            },

            // 3. Lógica de filtrado
            filterTable: function() {
                const searchTerm = this.searchInput.value.toLowerCase().trim();
                const categoryFilter = this.categorySelect.value;
                const statusFilter = this.statusSelect.value;

                this.productRows.forEach(row => {
                    const name = row.dataset.name;
                    const sku = row.dataset.sku;
                    const category = row.dataset.category;
                    const status = row.dataset.status;

                    const matchesSearch = name.includes(searchTerm) || sku.includes(searchTerm);
                    const matchesCategory = categoryFilter === 'all' || category.includes(categoryFilter);
                    const matchesStatus = statusFilter === 'all' || status === statusFilter;

                    // Mostrar u ocultar usando un operador ternario limpio
                    row.style.display = (matchesSearch && matchesCategory && matchesStatus) ? '' : 'none';
                });
            }
        };

        // Arrancar el módulo cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', () => ProductFilters.init());