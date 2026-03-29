<div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all">
        
        <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50 shrink-0">
            <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Product Information</h3>
            <button type="button" onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto flex-1" id="productForm">
            <input type="hidden" name="edit_id" id="edit_id">

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto *</label>
                        <input type="text" name="prod_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="hidden" name="prod_sku">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio Base *</label>
                            <input type="number" step="0.01" name="prod_price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría (Género) *</label>
                        <select name="prod_category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">Seleccionar Género</option>
                                <?php foreach ($categories_list as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                        </select>
                        <p class="text-[10px] text-gray-500 mt-1">Selecciona Hombre o Mujer.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="prod_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="active">Activo</option>
                            <option value="draft">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" name="prod_stock" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Minimo (Alerta)</label>
                        <input type="number" name="prod_low_stock" id="prod_low_stock" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Ej. 5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Visibilidad</label>
                        <select name="prod_visibility" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="visible">Visible (En todas partes)</option>
                            <option value="catalog">Solo en el catalogo</option>
                            <option value="hidden">Oculto</option>
                        </select>
                    </div>                            
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen del Producto</label>
                    <input type="file" name="prod_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all border border-gray-300 rounded-lg">
                    <p class="text-[10px] text-gray-400 mt-1">Sube una imagen representativa.</p>
                </div>
            </div>

            <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-200">
                <label class="flex items-center gap-2 cursor-pointer font-bold text-amber-800 mb-4">
                    <input type="checkbox" name="is_on_sale" id="saleToggle" onchange="toggleSaleFields()" class="w-5 h-5 rounded text-amber-600">
                    <i class="fas fa-tag"></i> ¿Aplicar Oferta?
                </label>
                
                <div id="saleFields" class="grid grid-cols-3 gap-4 hidden">
                    <div>
                        <label class="block text-xs font-bold text-amber-700 uppercase">Sale Price</label>
                        <input type="number" step="0.01" name="prod_sale_price" class="w-full p-2 border border-amber-200 rounded-lg outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-amber-700 uppercase">Start Date</label>
                        <input type="date" name="sale_start_at" min="<?php echo (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d'); ?>" class="w-full p-2 border border-amber-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-amber-700 uppercase">End Date</label>
                        <input type="date" name="sale_end_at" class="w-full p-2 border border-amber-200 rounded-lg text-sm">
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripcion</label>
                <textarea name="prod_short_desc" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 shrink-0">
                <button type="button" onclick="closeProductModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Cancelar</button>
                <button type="button" id="submitBtn" name="btn_save_product" title="Guardar producto" onclick="confirmarAccion(event, this)" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>