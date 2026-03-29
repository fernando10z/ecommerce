<div class="card mb-6">
    <div class="card-header bg-gray-50 flex flex-wrap gap-4 items-center p-4 border-b border-gray-200">
        <div class="search-box flex-1 min-w-[250px] relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="filter_search" placeholder="Buscar por nombre o SKU..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#10b981]">
        </div>
        
        <select id="filter_category" class="border border-gray-300 rounded-lg px-4 py-2 text-sm bg-white focus:outline-none focus:border-[#10b981]">
            <option value="all">Todas las categorías</option>
            <?php foreach($categories_list as $cat): ?>
                <option value="<?php echo htmlspecialchars(strtolower($cat['name'])); ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <select id="filter_status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm bg-white focus:outline-none focus:border-[#10b981]">
            <option value="all">Todos los estados</option>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
            <option value="eliminado">Eliminado (Front)</option>
        </select>
    </div>
</div>