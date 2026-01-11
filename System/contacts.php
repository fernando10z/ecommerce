<?php
session_start();
require_once 'conexion/conexion.php';

// Obtener datos de la organización
$stmt = $conn->prepare("SELECT * FROM organizations WHERE id = 1 LIMIT 1");
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$org) {
    $org = [
        'id' => 1,
        'name' => 'CRM Pro',
        'logo_url' => 'assets/images/logo.png',
        'primary_color' => '#10b981',
        'secondary_color' => '#059669'
    ];
}

$usuario = [
    'id' => 1,
    'nombre' => 'Fernando',
    'email' => 'fernando@ejemplo.com',
    'rol' => 'Administrador'
];

// Obtener empresas para el select
$stmtEmpresas = $conn->prepare("SELECT id, name FROM companies WHERE organization_id = :org_id AND deleted_at IS NULL ORDER BY name ASC");
$stmtEmpresas->bindParam(':org_id', $org['id'], PDO::PARAM_INT);
$stmtEmpresas->execute();
$empresas = $stmtEmpresas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $org['logo_url']; ?>" type="image/png">
    <title>Contactos | <?php echo htmlspecialchars($org['name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        :root {
            --primary: <?php echo $org['primary_color']; ?>;
            --primary-dark: <?php echo $org['secondary_color']; ?>;
            --primary-light: rgba(16, 185, 129, 0.1);
            --primary-hover: rgba(16, 185, 129, 0.05);
            
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --white: #ffffff;
            
            --sidebar-width: 280px;
            --header-height: 72px;
            
            --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        
        .breadcrumb-active { color: var(--gray-900); font-weight: 500; }
        
        .content { padding: 1.5rem; }
        
        /* PAGE HEADER */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .page-title i { color: var(--primary); }
        
        .page-actions { display: flex; gap: 0.75rem; }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.15s;
        }
        
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary { background: white; color: var(--gray-700); border: 1px solid var(--gray-300); }
        .btn-secondary:hover { background: var(--gray-50); }
        
        /* CARD */
        .card {
            background: var(--white);
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }
        
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .filters-row { display: flex; gap: 0.75rem; align-items: center; }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding: 0.5rem 1rem 0.5rem 2.25rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            width: 240px;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
        }
        
        .search-box i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.875rem;
        }
        
        .filter-select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            background: white;
        }
        
        /* TABLE */
        .table-responsive { overflow-x: auto; }
        
        .data-table { width: 100%; border-collapse: collapse; }
        
        .data-table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .data-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--gray-200);
            font-size: 0.875rem;
            color: var(--gray-700);
        }
        
        .data-table tbody tr:hover { background: var(--gray-50); }
        
        .contact-cell { display: flex; align-items: center; gap: 0.75rem; }
        
        .contact-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(16,185,129,0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8125rem;
        }
        
        .contact-name { font-weight: 500; color: var(--gray-900); }
        .contact-email { font-size: 0.75rem; color: var(--gray-500); }
        
        .badge {
            display: inline-flex;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .badge-dnc { background: #fef3c7; color: #92400e; }
        .badge-company { background: #e0e7ff; color: #3730a3; }
        
        .row-actions { display: flex; gap: 0.375rem; }
        
        .btn-action {
            width: 30px;
            height: 30px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8125rem;
            transition: all 0.15s;
        }
        
        .btn-edit { background: #dbeafe; color: #1d4ed8; }
        .btn-edit:hover { background: #bfdbfe; }
        .btn-delete { background: #fee2e2; color: #dc2626; }
        .btn-delete:hover { background: #fecaca; }
        
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.875rem 1.25rem;
            border-top: 1px solid var(--gray-200);
            background: var(--gray-50);
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        
        .pagination { display: flex; gap: 0.25rem; }
        
        .pagination button {
            padding: 0.375rem 0.75rem;
            border: 1px solid var(--gray-300);
            background: white;
            border-radius: 4px;
            font-size: 0.8125rem;
            cursor: pointer;
        }
        
        .pagination button:hover:not(:disabled) { background: var(--gray-100); }
        .pagination button.active { background: var(--primary); color: white; border-color: var(--primary); }
        .pagination button:disabled { opacity: 0.5; cursor: not-allowed; }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }
        
        .empty-state i { font-size: 2.5rem; color: var(--gray-300); margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1rem; color: var(--gray-700); margin-bottom: 0.25rem; }
        
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        
        .spinner {
            width: 32px;
            height: 32px;
            border: 3px solid var(--gray-200);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        /* TOAST */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .toast {
            padding: 0.875rem 1.25rem;
            border-radius: 6px;
            color: white;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .toast-success { background: #059669; }
        .toast-error { background: #dc2626; }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* MODAL */
        .modal {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        .modal.active { display: flex; }
        
        .modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            position: relative;
            background: white;
            border-radius: 8px;
            width: 100%;
            max-width: 480px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .modal-title { font-size: 1.125rem; font-weight: 600; }
        
        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: var(--gray-100);
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-close:hover { background: var(--gray-200); }
        
        .modal-body { padding: 1.25rem; }
        
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--gray-700); margin-bottom: 0.375rem; }
        
        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
        }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--gray-200);
            background: var(--gray-50);
        }
        
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .filters-row { flex-wrap: wrap; }
            .search-box input { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- SIDEBAR -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- MAIN -->
        <main class="main-content">
            <header class="header">
                <nav class="breadcrumb">
                    <span>Inicio</span>
                    <span>/</span>
                    <span class="breadcrumb-active">Contactos</span>
                </nav>
            </header>
            
            <div class="content">
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-users"></i>
                        Contactos
                    </h1>
                    <div class="page-actions">
                        <button class="btn btn-secondary" onclick="exportarPDF()">
                            <i class="fas fa-file-pdf"></i>
                            Exportar
                        </button>
                        <button class="btn btn-primary" onclick="openModal('modal-add')">
                            <i class="fas fa-plus"></i>
                            Nuevo Contacto
                        </button>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="filters-row">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchInput" placeholder="Buscar contactos...">
                            </div>
                            <select class="filter-select" id="filterStatus">
                                <option value="">Todos los estados</option>
                                <option value="active">Activos</option>
                                <option value="inactive">Inactivos</option>
                                <option value="do_not_contact">No contactar</option>
                            </select>
                        </div>
                        <div id="tableInfo">Cargando...</div>
                    </div>
                    
                    <div style="position: relative;">
                        <div class="loading-overlay" id="loadingOverlay">
                            <div class="spinner"></div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Contacto</th>
                                        <th>Teléfono</th>
                                        <th>Empresa</th>
                                        <th>Cargo</th>
                                        <th>Estado</th>
                                        <th style="width: 100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="contactsBody"></tbody>
                            </table>
                        </div>
                        
                        <div class="empty-state" id="emptyState" style="display: none;">
                            <i class="fas fa-users"></i>
                            <h3>No hay contactos</h3>
                            <p>Comienza agregando tu primer contacto</p>
                        </div>
                    </div>
                    
                    <div class="table-footer">
                        <div id="paginationInfo">-</div>
                        <div class="pagination" id="pagination"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <div class="toast-container" id="toastContainer"></div>
    <?php include 'modals/contacts/modal_add.php'; ?>
    <?php include 'modals/contacts/modal_edit.php'; ?>
    <?php include 'modals/contacts/modal_delete.php'; ?>

    <script>
        let currentPage = 1;
        const perPage = 10;
        let searchTimeout;
        
        document.addEventListener('DOMContentLoaded', function() {
            loadContacts();
            
            document.getElementById('searchInput').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => { currentPage = 1; loadContacts(); }, 300);
            });
            
            document.getElementById('filterStatus').addEventListener('change', function() {
                currentPage = 1;
                loadContacts();
            });
        });
        
        async function loadContacts() {
            showLoading(true);
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('filterStatus').value;
            
            try {
                const res = await fetch(`actions/contacts/get.php?page=${currentPage}&per_page=${perPage}&search=${encodeURIComponent(search)}&status=${status}`);
                const data = await res.json();
                
                if (data.success) {
                    renderContacts(data.contacts);
                    updatePagination(data.total, data.pages);
                    document.getElementById('tableInfo').textContent = `${data.total} contacto${data.total !== 1 ? 's' : ''}`;
                } else {
                    showToast(data.message || 'Error al cargar', 'error');
                }
            } catch (e) {
                showToast('Error de conexión', 'error');
            }
            showLoading(false);
        }
        
        function renderContacts(contacts) {
            const tbody = document.getElementById('contactsBody');
            const empty = document.getElementById('emptyState');
            
            if (!contacts.length) {
                tbody.innerHTML = '';
                empty.style.display = 'block';
                return;
            }
            
            empty.style.display = 'none';
            tbody.innerHTML = contacts.map(c => {
                const initials = ((c.first_name?.[0] || '') + (c.last_name?.[0] || '')).toUpperCase();
                const badges = { active: 'badge-active', inactive: 'badge-inactive', do_not_contact: 'badge-dnc' };
                const labels = { active: 'Activo', inactive: 'Inactivo', do_not_contact: 'No contactar' };
                
                return `<tr>
                    <td>
                        <div class="contact-cell">
                            <div class="contact-avatar">${initials}</div>
                            <div>
                                <div class="contact-name">${esc(c.first_name)} ${esc(c.last_name)}</div>
                                <div class="contact-email">${esc(c.email || '-')}</div>
                            </div>
                        </div>
                    </td>
                    <td>${esc(c.phone || c.mobile || '-')}</td>
                    <td>${c.company_name ? `<span class="badge badge-company">${esc(c.company_name)}</span>` : '-'}</td>
                    <td>${esc(c.job_title || '-')}</td>
                    <td><span class="badge ${badges[c.status] || 'badge-active'}">${labels[c.status] || 'Activo'}</span></td>
                    <td>
                        <div class="row-actions">
                            <button class="btn-action btn-edit" onclick="editContact(${c.id})"><i class="fas fa-pen"></i></button>
                            <button class="btn-action btn-delete" onclick="confirmDelete(${c.id}, '${esc(c.first_name)} ${esc(c.last_name)}')"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        }
        
        async function createContact(e) {
            e.preventDefault();
            const form = e.target;
            try {
                const res = await fetch('actions/contactos/create.php', { method: 'POST', body: new FormData(form) });
                const data = await res.json();
                if (data.success) {
                    showToast('Contacto creado', 'success');
                    closeModal('modal-add');
                    form.reset();
                    loadContacts();
                } else {
                    showToast(data.message || 'Error', 'error');
                }
            } catch (e) { showToast('Error de conexión', 'error'); }
        }
        
        async function editContact(id) {
            try {
                const res = await fetch(`actions/contactos/get.php?id=${id}`);
                const data = await res.json();
                if (data.success && data.contact) {
                    const c = data.contact;
                    document.getElementById('edit_id').value = c.id;
                    document.getElementById('edit_first_name').value = c.first_name || '';
                    document.getElementById('edit_last_name').value = c.last_name || '';
                    document.getElementById('edit_email').value = c.email || '';
                    document.getElementById('edit_phone').value = c.phone || '';
                    document.getElementById('edit_mobile').value = c.mobile || '';
                    document.getElementById('edit_company_id').value = c.company_id || '';
                    document.getElementById('edit_job_title').value = c.job_title || '';
                    document.getElementById('edit_status').value = c.status || 'active';
                    openModal('modal-edit');
                }
            } catch (e) { showToast('Error de conexión', 'error'); }
        }
        
        async function updateContact(e) {
            e.preventDefault();
            try {
                const res = await fetch('actions/contactos/update.php', { method: 'POST', body: new FormData(e.target) });
                const data = await res.json();
                if (data.success) {
                    showToast('Contacto actualizado', 'success');
                    closeModal('modal-edit');
                    loadContacts();
                } else {
                    showToast(data.message || 'Error', 'error');
                }
            } catch (e) { showToast('Error de conexión', 'error'); }
        }
        
        function confirmDelete(id, name) {
            document.getElementById('delete_id').value = id;
            document.getElementById('delete_contact_name').textContent = name;
            openModal('modal-delete');
        }
        
        async function deleteContact() {
            const id = document.getElementById('delete_id').value;
            try {
                const res = await fetch('actions/contactos/delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const data = await res.json();
                if (data.success) {
                    showToast('Contacto eliminado', 'success');
                    closeModal('modal-delete');
                    loadContacts();
                } else {
                    showToast(data.message || 'Error', 'error');
                }
            } catch (e) { showToast('Error de conexión', 'error'); }
        }
        
        function exportarPDF() {
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('filterStatus').value;
            window.open(`actions/contactos/export_pdf.php?search=${encodeURIComponent(search)}&status=${status}`, '_blank');
        }
        
        function updatePagination(total, pages) {
            const cont = document.getElementById('pagination');
            const from = total > 0 ? ((currentPage - 1) * perPage) + 1 : 0;
            const to = Math.min(currentPage * perPage, total);
            document.getElementById('paginationInfo').textContent = total > 0 ? `${from}-${to} de ${total}` : 'Sin resultados';
            
            if (pages <= 1) { cont.innerHTML = ''; return; }
            
            let html = `<button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;
            for (let i = 1; i <= pages; i++) {
                if (i === 1 || i === pages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    html += `<button onclick="goToPage(${i})" class="${i === currentPage ? 'active' : ''}">${i}</button>`;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    html += `<button disabled>...</button>`;
                }
            }
            html += `<button onclick="goToPage(${currentPage + 1})" ${currentPage === pages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
            cont.innerHTML = html;
        }
        
        function goToPage(p) { currentPage = p; loadContacts(); }
        function openModal(id) { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }
        function showLoading(show) { document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none'; }
        function esc(t) { if (!t) return ''; const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
        
        function showToast(msg, type = 'success') {
            const c = document.getElementById('toastContainer');
            const t = document.createElement('div');
            t.className = `toast toast-${type}`;
            t.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${msg}</span>`;
            c.appendChild(t);
            setTimeout(() => t.remove(), 3000);
        }
    </script>
</body>
</html>
<?php $conn = null; ?>