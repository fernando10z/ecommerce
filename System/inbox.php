<?php
session_start();
require_once 'conexion/conexion.php';

// Obtener datos de la organización
$stmt = $conn->prepare("SELECT * FROM `organizations` LIMIT 1");
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$org) {
    $org = [
        'name' => 'CRM Pro',
        'logo_url' => 'assets/images/collab.png',
        'primary_color' => '#10b981',
        'secondary_color' => '#059669'
    ];
}

// Datos del usuario (en producción vendría de sesión)
$usuario = [
    'id' => 1,
    'nombre' => 'Fernando',
    'email' => 'fernando@ejemplo.com',
    'rol' => 'Administrador'
];

// Filtro de canal (si viene por GET)
$canalFiltro = isset($_GET['channel']) ? $_GET['channel'] : 'all';

// Construir query de conversaciones
$sqlConv = "
    SELECT 
        ic.id,
        ic.uuid,
        ic.channel,
        ic.status,
        ic.priority,
        ic.subject,
        ic.last_message_preview,
        ic.last_message_at,
        ic.is_read,
        c.id as contact_id,
        c.first_name,
        c.last_name,
        c.email as contact_email,
        c.phone as contact_phone,
        c.job_title,
        co.name as company_name
    FROM inbox_conversations ic
    LEFT JOIN contacts c ON ic.contact_id = c.id
    LEFT JOIN companies co ON c.company_id = co.id
    WHERE ic.organization_id = :org_id
";

if ($canalFiltro !== 'all') {
    $sqlConv .= " AND ic.channel = :channel";
}

$sqlConv .= " ORDER BY ic.last_message_at DESC";

$stmtConv = $conn->prepare($sqlConv);
$stmtConv->bindParam(':org_id', $org['id'], PDO::PARAM_INT);
if ($canalFiltro !== 'all') {
    $stmtConv->bindParam(':channel', $canalFiltro, PDO::PARAM_STR);
}
$stmtConv->execute();
$conversaciones = $stmtConv->fetchAll(PDO::FETCH_ASSOC);

// Contar por canal
$stmtCount = $conn->prepare("
    SELECT 
        channel,
        COUNT(*) as total
    FROM inbox_conversations 
    WHERE organization_id = :org_id
    GROUP BY channel
");
$stmtCount->bindParam(':org_id', $org['id'], PDO::PARAM_INT);
$stmtCount->execute();
$conteoCanales = $stmtCount->fetchAll(PDO::FETCH_KEY_PAIR);

$totalConversaciones = array_sum($conteoCanales);

// Obtener conversación activa (primera o la que venga por GET)
$conversacionActiva = null;
$mensajesActivos = [];

if (!empty($conversaciones)) {
    $convId = isset($_GET['conv']) ? (int)$_GET['conv'] : $conversaciones[0]['id'];
    
    // Buscar la conversación activa
    foreach ($conversaciones as $conv) {
        if ($conv['id'] == $convId) {
            $conversacionActiva = $conv;
            break;
        }
    }
    
    // Si no se encontró, usar la primera
    if (!$conversacionActiva) {
        $conversacionActiva = $conversaciones[0];
        $convId = $conversacionActiva['id'];
    }
    
    // Obtener mensajes de la conversación activa
    $stmtMsg = $conn->prepare("
        SELECT 
            id,
            direction,
            sender_type,
            sender_name,
            sender_email,
            content,
            content_html,
            message_type,
            is_read,
            created_at
        FROM inbox_messages 
        WHERE conversation_id = :conv_id
        ORDER BY created_at ASC
    ");
    $stmtMsg->bindParam(':conv_id', $convId, PDO::PARAM_INT);
    $stmtMsg->execute();
    $mensajesActivos = $stmtMsg->fetchAll(PDO::FETCH_ASSOC);
    
    // Marcar conversación como leída
    $stmtUpdate = $conn->prepare("UPDATE inbox_conversations SET is_read = 1 WHERE id = :conv_id");
    $stmtUpdate->bindParam(':conv_id', $convId, PDO::PARAM_INT);
    $stmtUpdate->execute();
    
    // Marcar mensajes como leídos
    $stmtUpdateMsg = $conn->prepare("UPDATE inbox_messages SET is_read = 1, read_at = NOW() WHERE conversation_id = :conv_id AND is_read = 0");
    $stmtUpdateMsg->bindParam(':conv_id', $convId, PDO::PARAM_INT);
    $stmtUpdateMsg->execute();
}

// Función helper para obtener iniciales
function getIniciales($nombre, $apellido = '') {
    $iniciales = strtoupper(substr($nombre, 0, 1));
    if (!empty($apellido)) {
        $iniciales .= strtoupper(substr($apellido, 0, 1));
    }
    return $iniciales;
}

// Función para formatear fecha
function formatearFecha($fecha) {
    $dt = new DateTime($fecha);
    $hoy = new DateTime();
    $ayer = new DateTime('yesterday');
    
    if ($dt->format('Y-m-d') === $hoy->format('Y-m-d')) {
        return $dt->format('H:i');
    } elseif ($dt->format('Y-m-d') === $ayer->format('Y-m-d')) {
        return 'Ayer';
    } else {
        return $dt->format('d M');
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $org['logo_url']; ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Inbox | <?php echo $org['name']; ?></title>
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
            
            --blue-500: #3b82f6;
            --blue-100: #dbeafe;
            --green-500: #22c55e;
            --green-100: #dcfce7;
            --purple-500: #8b5cf6;
            --purple-100: #ede9fe;
            --orange-500: #f97316;
            --orange-100: #ffedd5;
            --red-500: #ef4444;
            --red-100: #fee2e2;
            
            --sidebar-width: 280px;
            --header-height: 72px;
            --inbox-list-width: 380px;
            
            --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-sans);
            background: var(--white);
            color: var(--gray-900);
            -webkit-font-smoothing: antialiased;
        }
        
        .app-container { display: flex; min-height: 100vh; }

        /* SIDEBAR - Incluido desde archivo externo */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--white);
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow: hidden;
            z-index: 50;
        }
        
        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0 1.75rem;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .sidebar-logo-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            flex-shrink: 0;
        }
        
        .sidebar-logo { width: 24px; height: 24px; object-fit: contain; }
        .sidebar-brand { flex: 1; min-width: 0; }
        .sidebar-title { font-size: 1.125rem; font-weight: 600; color: var(--gray-900); line-height: 1.2; }
        .sidebar-tagline { font-size: 0.75rem; color: var(--gray-500); margin-top: 0.125rem; }
        
        .sidebar-nav { flex: 1; padding: 1.5rem 1rem; overflow-y: auto; }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 3px; }
        
        .nav-section { margin-bottom: 1.75rem; }
        .nav-section:last-child { margin-bottom: 0; }
        
        .nav-section-title {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0 0.75rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-section-title::before {
            content: '';
            width: 12px;
            height: 2px;
            background: var(--gray-300);
            border-radius: 1px;
        }
        
        .nav-items { display: flex; flex-direction: column; gap: 0.25rem; }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.75rem;
            color: var(--gray-700);
            text-decoration: none;
            font-size: 0.9375rem;
            font-weight: 500;
            transition: all 0.15s ease;
            border-radius: 8px;
            cursor: pointer;
            position: relative;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--primary);
            border-radius: 0 2px 2px 0;
            transition: height 0.15s ease;
        }
        
        .nav-item:hover { background: var(--primary-hover); color: var(--gray-900); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-item.active::before { height: 20px; }
        
        .nav-icon { width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .nav-label { flex: 1; }
        
        .nav-badge {
            padding: 0.125rem 0.5rem;
            font-size: 0.6875rem;
            font-weight: 600;
            background: var(--gray-100);
            color: var(--gray-600);
            border-radius: 10px;
        }
        
        .nav-item.active .nav-badge { background: var(--primary); color: var(--white); }
        .nav-badge.warning { background: #fef3c7; color: #d97706; }
        
        .nav-arrow {
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: var(--gray-400);
            transition: transform 0.2s ease;
        }
        
        .nav-item.has-submenu.open .nav-arrow { transform: rotate(90deg); }
        
        .nav-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            margin-left: 2.25rem;
            margin-top: 0.25rem;
        }
        
        .nav-submenu.open { max-height: 500px; }
        
        .nav-subitem {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            color: var(--gray-600);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            border-radius: 6px;
            margin-bottom: 0.125rem;
        }
        
        .nav-subitem::before {
            content: '';
            width: 4px;
            height: 4px;
            background: var(--gray-400);
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .nav-subitem:hover { background: var(--primary-hover); color: var(--gray-900); }
        .nav-subitem:hover::before { background: var(--primary); }
        .nav-subitem.active { background: var(--primary-light); color: var(--primary); }
        .nav-subitem.active::before { background: var(--primary); }
        
        .sidebar-footer {
            padding: 1rem 1.75rem;
            border-top: 1px solid var(--gray-200);
            background: var(--gray-50);
        }
        
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .sidebar-user:hover { border-color: var(--gray-300); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); }
        
        .sidebar-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
            flex-shrink: 0;
            position: relative;
        }
        
        .sidebar-avatar::after {
            content: '';
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 10px;
            height: 10px;
            background: #10b981;
            border: 2px solid var(--white);
            border-radius: 50%;
        }
        
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name { font-size: 0.875rem; font-weight: 600; color: var(--gray-900); }
        .sidebar-user-role { font-size: 0.75rem; color: var(--gray-500); margin-top: 0.125rem; }
        .sidebar-user-arrow { width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: var(--gray-400); }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            background: var(--white);
            height: 100vh;
            overflow: hidden;
        }
        
        .header {
            height: var(--header-height);
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            flex-shrink: 0;
        }
        
        .header-title { font-size: 1.125rem; font-weight: 600; color: var(--gray-900); }
        .header-right { display: flex; align-items: center; gap: 0.75rem; }
        
        .header-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .header-btn:hover { background: var(--white); color: var(--gray-900); border-color: var(--gray-300); }
        
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            font-family: var(--font-sans);
            color: var(--white);
            background: var(--primary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .btn-primary:hover { background: var(--primary-dark); }

        /* INBOX CONTAINER */
        .inbox-container { flex: 1; display: flex; overflow: hidden; }
        
        /* INBOX LIST */
        .inbox-list {
            width: var(--inbox-list-width);
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            background: var(--white);
            flex-shrink: 0;
        }
        
        .inbox-list-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--gray-200); }
        .inbox-search { position: relative; }
        
        .inbox-search-input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            font-size: 0.875rem;
            font-family: var(--font-sans);
            color: var(--gray-900);
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            outline: none;
            transition: all 0.15s ease;
        }
        
        .inbox-search-input::placeholder { color: var(--gray-400); }
        .inbox-search-input:focus { background: var(--white); border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
        
        .inbox-search-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.875rem;
        }
        
        .inbox-filters {
            display: flex;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
            overflow-x: auto;
        }
        
        .inbox-filters::-webkit-scrollbar { display: none; }
        
        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--gray-600);
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            text-decoration: none;
        }
        
        .filter-chip:hover { background: var(--gray-100); border-color: var(--gray-300); }
        .filter-chip.active { background: var(--primary-light); border-color: var(--primary); color: var(--primary); }
        .filter-chip i { font-size: 0.75rem; }
        .filter-chip .count { padding: 0.0625rem 0.375rem; font-size: 0.6875rem; background: var(--gray-200); border-radius: 10px; }
        .filter-chip.active .count { background: var(--primary); color: var(--white); }
        
        .conversations-list { flex: 1; overflow-y: auto; }
        .conversations-list::-webkit-scrollbar { width: 6px; }
        .conversations-list::-webkit-scrollbar-track { background: transparent; }
        .conversations-list::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 3px; }
        
        .conversation-item {
            display: flex;
            gap: 0.875rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
            text-decoration: none;
            color: inherit;
        }
        
        .conversation-item:hover { background: var(--gray-50); }
        .conversation-item.active { background: var(--primary-light); }
        .conversation-item.unread { background: var(--blue-100); }
        .conversation-item.unread:hover { background: #bfdbfe; }
        
        .conversation-item.unread::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--blue-500);
        }
        
        .conversation-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
            flex-shrink: 0;
            position: relative;
        }
        
        .conversation-avatar.email { background: var(--blue-100); color: var(--blue-500); }
        .conversation-avatar.whatsapp { background: var(--green-100); color: var(--green-500); }
        .conversation-avatar.sms { background: var(--purple-100); color: var(--purple-500); }
        .conversation-avatar.telegram { background: var(--blue-100); color: #0088cc; }
        .conversation-avatar.webchat { background: var(--orange-100); color: var(--orange-500); }
        
        .channel-indicator {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.5625rem;
            border: 2px solid var(--white);
        }
        
        .channel-indicator.email { background: var(--blue-500); color: var(--white); }
        .channel-indicator.whatsapp { background: #25d366; color: var(--white); }
        .channel-indicator.sms { background: var(--purple-500); color: var(--white); }
        .channel-indicator.telegram { background: #0088cc; color: var(--white); }
        .channel-indicator.webchat { background: var(--orange-500); color: var(--white); }
        
        .conversation-content { flex: 1; min-width: 0; }
        .conversation-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem; }
        
        .conversation-name {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--gray-900);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .conversation-time { font-size: 0.75rem; color: var(--gray-500); flex-shrink: 0; margin-left: 0.5rem; }
        
        .conversation-subject {
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--gray-700);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.25rem;
        }
        
        .conversation-preview {
            font-size: 0.8125rem;
            color: var(--gray-500);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.4;
        }
        
        .conversation-meta { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem; }
        
        .priority-badge {
            padding: 0.125rem 0.5rem;
            font-size: 0.6875rem;
            font-weight: 600;
            border-radius: 4px;
            text-transform: uppercase;
        }
        
        .priority-badge.high, .priority-badge.urgent { background: var(--red-100); color: var(--red-500); }
        .priority-badge.normal { background: var(--gray-100); color: var(--gray-600); }

        /* INBOX DETAIL */
        .inbox-detail {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--gray-50);
            min-width: 0;
        }
        
        .detail-header {
            padding: 1.25rem 1.5rem;
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }
        
        .detail-contact { display: flex; align-items: center; gap: 1rem; }
        
        .detail-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 600;
            background: var(--blue-100);
            color: var(--blue-500);
        }
        
        .detail-info h3 { font-size: 1rem; font-weight: 600; color: var(--gray-900); margin-bottom: 0.25rem; }
        .detail-info p { font-size: 0.8125rem; color: var(--gray-500); }
        
        .detail-actions { display: flex; gap: 0.5rem; }
        
        .detail-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            color: var(--gray-500);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .detail-btn:hover { background: var(--gray-50); color: var(--gray-700); border-color: var(--gray-300); }
        
        .detail-subject { padding: 1rem 1.5rem; background: var(--white); border-bottom: 1px solid var(--gray-200); }
        .detail-subject h2 { font-size: 1.125rem; font-weight: 600; color: var(--gray-900); }
        
        .messages-timeline { flex: 1; overflow-y: auto; padding: 1.5rem; }
        .messages-timeline::-webkit-scrollbar { width: 6px; }
        .messages-timeline::-webkit-scrollbar-track { background: transparent; }
        .messages-timeline::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 3px; }
        
        .message-item { margin-bottom: 1.5rem; }
        .message-item:last-child { margin-bottom: 0; }
        
        .message-bubble {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.25rem;
            max-width: 85%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .message-item.inbound .message-bubble { margin-right: auto; border-bottom-left-radius: 4px; }
        
        .message-item.outbound .message-bubble {
            margin-left: auto;
            background: var(--primary-light);
            border-color: var(--primary);
            border-bottom-right-radius: 4px;
        }
        
        .message-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--gray-100);
        }
        
        .message-item.outbound .message-header { border-bottom-color: rgba(16, 185, 129, 0.2); }
        
        .message-sender { display: flex; align-items: center; gap: 0.5rem; }
        
        .message-sender-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6875rem;
            font-weight: 600;
        }
        
        .message-item.inbound .message-sender-avatar { background: var(--blue-100); color: var(--blue-500); }
        .message-item.outbound .message-sender-avatar { background: var(--primary); color: var(--white); }
        
        .message-sender-name { font-size: 0.8125rem; font-weight: 600; color: var(--gray-900); }
        .message-date { font-size: 0.75rem; color: var(--gray-500); }
        .message-body { font-size: 0.9375rem; color: var(--gray-700); line-height: 1.6; }
        
        /* COMPOSER */
        .message-composer { padding: 1.25rem 1.5rem; background: var(--white); border-top: 1px solid var(--gray-200); }
        
        .composer-wrapper {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .composer-toolbar {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--white);
        }
        
        .toolbar-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            border-radius: 6px;
            color: var(--gray-500);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .toolbar-btn:hover { background: var(--gray-100); color: var(--gray-700); }
        .toolbar-divider { width: 1px; height: 20px; background: var(--gray-200); margin: 0 0.5rem; }
        
        .composer-textarea {
            width: 100%;
            min-height: 100px;
            padding: 1rem;
            font-size: 0.9375rem;
            font-family: var(--font-sans);
            color: var(--gray-900);
            background: var(--gray-50);
            border: none;
            outline: none;
            resize: none;
            line-height: 1.6;
        }
        
        .composer-textarea::placeholder { color: var(--gray-400); }
        
        .composer-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: var(--white);
            border-top: 1px solid var(--gray-200);
        }
        
        .composer-options { display: flex; align-items: center; gap: 0.75rem; }
        
        .composer-option {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            color: var(--gray-500);
            cursor: pointer;
            transition: color 0.15s ease;
        }
        
        .composer-option:hover { color: var(--gray-700); }
        
        .btn-send {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            font-family: var(--font-sans);
            color: var(--white);
            background: var(--primary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .btn-send:hover { background: var(--primary-dark); }

        /* CONTACT SIDEBAR */
        .contact-sidebar {
            width: 280px;
            background: var(--white);
            border-left: 1px solid var(--gray-200);
            flex-shrink: 0;
            overflow-y: auto;
        }
        
        .contact-sidebar-header { padding: 1.25rem; text-align: center; border-bottom: 1px solid var(--gray-200); }
        
        .contact-sidebar-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 600;
            background: var(--blue-100);
            color: var(--blue-500);
            margin: 0 auto 0.75rem;
        }
        
        .contact-sidebar-name { font-size: 1rem; font-weight: 600; color: var(--gray-900); margin-bottom: 0.25rem; }
        .contact-sidebar-company { font-size: 0.8125rem; color: var(--gray-500); }
        
        .contact-sidebar-section { padding: 1rem 1.25rem; border-bottom: 1px solid var(--gray-100); }
        
        .contact-sidebar-section-title {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }
        
        .contact-field { display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem; }
        .contact-field:last-child { margin-bottom: 0; }
        
        .contact-field-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-100);
            border-radius: 8px;
            color: var(--gray-500);
            font-size: 0.8125rem;
            flex-shrink: 0;
        }
        
        .contact-field-content { flex: 1; min-width: 0; }
        .contact-field-label { font-size: 0.6875rem; color: var(--gray-500); margin-bottom: 0.125rem; }
        .contact-field-value { font-size: 0.875rem; color: var(--gray-900); word-break: break-word; }
        .contact-field-value a { color: var(--primary); text-decoration: none; }
        .contact-field-value a:hover { text-decoration: underline; }
        
        .contact-quick-actions { display: flex; gap: 0.5rem; padding: 1rem 1.25rem; }
        
        .quick-action-btn {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.375rem;
            padding: 0.75rem;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            color: var(--gray-600);
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        
        .quick-action-btn:hover { background: var(--primary-light); border-color: var(--primary); color: var(--primary); }
        .quick-action-btn i { font-size: 1rem; }

        /* EMPTY STATE */
        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }
        
        .empty-state i { font-size: 3rem; color: var(--gray-300); margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1.125rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem; }
        .empty-state p { font-size: 0.875rem; color: var(--gray-500); }

        /* RESPONSIVE */
        @media (max-width: 1400px) { .contact-sidebar { display: none; } }
        
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; z-index: 100; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
        
        @media (max-width: 768px) {
            .inbox-list { width: 100%; }
            .inbox-detail { display: none; }
            .inbox-detail.active { display: flex; width: 100%; }
            .inbox-list.hidden { display: none; }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <header class="header">
                <div class="header-left">
                    <h1 class="header-title">Inbox</h1>
                </div>
                
                <div class="header-right">
                    <button class="btn-primary" onclick="openNewMessage()">
                        <i class="fas fa-pen"></i>
                        <span>Nuevo mensaje</span>
                    </button>
                    <button class="header-btn">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </header>
            
            <div class="inbox-container">
                <!-- Panel izquierdo: Lista de conversaciones -->
                <div class="inbox-list" id="inboxList">
                    <div class="inbox-list-header">
                        <div class="inbox-search">
                            <i class="inbox-search-icon fas fa-search"></i>
                            <input type="text" class="inbox-search-input" id="searchInput" placeholder="Buscar conversaciones...">
                        </div>
                    </div>
                    
                    <div class="inbox-filters">
                        <a href="inbox.php" class="filter-chip <?php echo $canalFiltro === 'all' ? 'active' : ''; ?>">
                            <i class="fas fa-inbox"></i>
                            <span>Todos</span>
                            <span class="count"><?php echo $totalConversaciones; ?></span>
                        </a>
                        <a href="inbox.php?channel=email" class="filter-chip <?php echo $canalFiltro === 'email' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                            <span class="count"><?php echo $conteoCanales['email'] ?? 0; ?></span>
                        </a>
                        <a href="inbox.php?channel=whatsapp" class="filter-chip <?php echo $canalFiltro === 'whatsapp' ? 'active' : ''; ?>">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                            <span class="count"><?php echo $conteoCanales['whatsapp'] ?? 0; ?></span>
                        </a>
                        <a href="inbox.php?channel=sms" class="filter-chip <?php echo $canalFiltro === 'sms' ? 'active' : ''; ?>">
                            <i class="fas fa-sms"></i>
                            <span>SMS</span>
                            <span class="count"><?php echo $conteoCanales['sms'] ?? 0; ?></span>
                        </a>
                    </div>
                    
                    <div class="conversations-list">
                        <?php if (empty($conversaciones)): ?>
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h3>No hay conversaciones</h3>
                                <p>Las conversaciones aparecerán aquí</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($conversaciones as $conv): ?>
                                <?php 
                                    $isActive = ($conversacionActiva && $conv['id'] == $conversacionActiva['id']);
                                    $isUnread = !$conv['is_read'];
                                    $iniciales = getIniciales($conv['first_name'], $conv['last_name']);
                                    $nombreCompleto = trim($conv['first_name'] . ' ' . $conv['last_name']);
                                ?>
                                <a href="inbox.php?conv=<?php echo $conv['id']; ?><?php echo $canalFiltro !== 'all' ? '&channel=' . $canalFiltro : ''; ?>" 
                                   class="conversation-item <?php echo $isUnread ? 'unread' : ''; ?> <?php echo $isActive ? 'active' : ''; ?>">
                                    <div class="conversation-avatar <?php echo $conv['channel']; ?>">
                                        <?php echo $iniciales; ?>
                                        <div class="channel-indicator <?php echo $conv['channel']; ?>">
                                            <?php if ($conv['channel'] === 'email'): ?>
                                                <i class="fas fa-envelope"></i>
                                            <?php elseif ($conv['channel'] === 'whatsapp'): ?>
                                                <i class="fab fa-whatsapp"></i>
                                            <?php elseif ($conv['channel'] === 'sms'): ?>
                                                <i class="fas fa-sms"></i>
                                            <?php elseif ($conv['channel'] === 'telegram'): ?>
                                                <i class="fab fa-telegram"></i>
                                            <?php else: ?>
                                                <i class="fas fa-comment"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="conversation-content">
                                        <div class="conversation-header">
                                            <span class="conversation-name"><?php echo htmlspecialchars($nombreCompleto); ?></span>
                                            <span class="conversation-time"><?php echo formatearFecha($conv['last_message_at']); ?></span>
                                        </div>
                                        <?php if (!empty($conv['subject'])): ?>
                                            <div class="conversation-subject"><?php echo htmlspecialchars($conv['subject']); ?></div>
                                        <?php endif; ?>
                                        <div class="conversation-preview"><?php echo htmlspecialchars($conv['last_message_preview']); ?></div>
                                        <?php if ($conv['priority'] === 'high' || $conv['priority'] === 'urgent'): ?>
                                            <div class="conversation-meta">
                                                <span class="priority-badge <?php echo $conv['priority']; ?>">
                                                    <?php echo $conv['priority'] === 'urgent' ? 'Urgente' : 'Alta'; ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Panel central: Detalle del mensaje -->
                <?php if ($conversacionActiva && !empty($mensajesActivos)): ?>
                    <?php 
                        $inicialesActivo = getIniciales($conversacionActiva['first_name'], $conversacionActiva['last_name']);
                        $nombreActivo = trim($conversacionActiva['first_name'] . ' ' . $conversacionActiva['last_name']);
                    ?>
                    <div class="inbox-detail" id="inboxDetail">
                        <div class="detail-header">
                            <div class="detail-contact">
                                <div class="detail-avatar"><?php echo $inicialesActivo; ?></div>
                                <div class="detail-info">
                                    <h3><?php echo htmlspecialchars($nombreActivo); ?></h3>
                                    <p><?php echo htmlspecialchars($conversacionActiva['contact_email']); ?></p>
                                </div>
                            </div>
                            <div class="detail-actions">
                                <button class="detail-btn" title="Archivar"><i class="fas fa-archive"></i></button>
                                <button class="detail-btn" title="Marcar como no leído"><i class="fas fa-envelope"></i></button>
                                <button class="detail-btn" title="Eliminar"><i class="fas fa-trash"></i></button>
                                <button class="detail-btn" title="Más opciones"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
                        </div>
                        
                        <?php if (!empty($conversacionActiva['subject'])): ?>
                            <div class="detail-subject">
                                <h2><?php echo htmlspecialchars($conversacionActiva['subject']); ?></h2>
                            </div>
                        <?php endif; ?>
                        
                        <div class="messages-timeline">
                            <?php foreach ($mensajesActivos as $msg): ?>
                                <div class="message-item <?php echo $msg['direction']; ?>">
                                    <div class="message-bubble">
                                        <div class="message-header">
                                            <div class="message-sender">
                                                <div class="message-sender-avatar">
                                                    <?php echo $msg['direction'] === 'inbound' ? $inicialesActivo : 'Yo'; ?>
                                                </div>
                                                <span class="message-sender-name">
                                                    <?php echo $msg['direction'] === 'inbound' ? htmlspecialchars($msg['sender_name']) : 'Tú'; ?>
                                                </span>
                                            </div>
                                            <span class="message-date">
                                                <?php echo date('d M Y, H:i', strtotime($msg['created_at'])); ?>
                                            </span>
                                        </div>
                                        <div class="message-body">
                                            <?php 
                                                if (!empty($msg['content_html'])) {
                                                    echo $msg['content_html'];
                                                } else {
                                                    echo nl2br(htmlspecialchars($msg['content']));
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="message-composer">
                            <form id="replyForm" method="POST" action="actions/inbox/inbox_send_message.php">
                                <input type="hidden" name="conversation_id" value="<?php echo $conversacionActiva['id']; ?>">
                                <div class="composer-wrapper">
                                    <div class="composer-toolbar">
                                        <button type="button" class="toolbar-btn" title="Negrita"><i class="fas fa-bold"></i></button>
                                        <button type="button" class="toolbar-btn" title="Cursiva"><i class="fas fa-italic"></i></button>
                                        <button type="button" class="toolbar-btn" title="Lista"><i class="fas fa-list-ul"></i></button>
                                        <div class="toolbar-divider"></div>
                                        <button type="button" class="toolbar-btn" title="Adjuntar archivo"><i class="fas fa-paperclip"></i></button>
                                        <button type="button" class="toolbar-btn" title="Insertar imagen"><i class="fas fa-image"></i></button>
                                        <button type="button" class="toolbar-btn" title="Insertar enlace"><i class="fas fa-link"></i></button>
                                        <div class="toolbar-divider"></div>
                                        <button type="button" class="toolbar-btn" title="Respuestas rápidas"><i class="fas fa-bolt"></i></button>
                                        <button type="button" class="toolbar-btn" title="Plantillas"><i class="fas fa-file-alt"></i></button>
                                    </div>
                                    <textarea class="composer-textarea" name="message" placeholder="Escribe tu respuesta..." required></textarea>
                                    <div class="composer-footer">
                                        <div class="composer-options">
                                            <label class="composer-option">
                                                <i class="fas fa-paper-plane"></i>
                                                <span>Enviar como <?php echo $conversacionActiva['channel']; ?></span>
                                            </label>
                                        </div>
                                        <button type="submit" class="btn-send">
                                            <i class="fas fa-paper-plane"></i>
                                            <span>Enviar</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Panel derecho: Info del contacto -->
                    <div class="contact-sidebar">
                        <div class="contact-sidebar-header">
                            <div class="contact-sidebar-avatar"><?php echo $inicialesActivo; ?></div>
                            <div class="contact-sidebar-name"><?php echo htmlspecialchars($nombreActivo); ?></div>
                            <div class="contact-sidebar-company"><?php echo htmlspecialchars($conversacionActiva['company_name'] ?? 'Sin empresa'); ?></div>
                        </div>
                        
                        <div class="contact-quick-actions">
                            <button class="quick-action-btn"><i class="fas fa-phone"></i><span>Llamar</span></button>
                            <button class="quick-action-btn"><i class="fas fa-video"></i><span>Reunión</span></button>
                        </div>
                        
                        <div class="contact-sidebar-section">
                            <div class="contact-sidebar-section-title">Información de contacto</div>
                            
                            <div class="contact-field">
                                <div class="contact-field-icon"><i class="fas fa-envelope"></i></div>
                                <div class="contact-field-content">
                                    <div class="contact-field-label">Email</div>
                                    <div class="contact-field-value">
                                        <a href="mailto:<?php echo $conversacionActiva['contact_email']; ?>">
                                            <?php echo htmlspecialchars($conversacionActiva['contact_email']); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($conversacionActiva['contact_phone'])): ?>
                            <div class="contact-field">
                                <div class="contact-field-icon"><i class="fas fa-phone"></i></div>
                                <div class="contact-field-content">
                                    <div class="contact-field-label">Teléfono</div>
                                    <div class="contact-field-value"><?php echo htmlspecialchars($conversacionActiva['contact_phone']); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($conversacionActiva['job_title'])): ?>
                            <div class="contact-field">
                                <div class="contact-field-icon"><i class="fas fa-briefcase"></i></div>
                                <div class="contact-field-content">
                                    <div class="contact-field-label">Cargo</div>
                                    <div class="contact-field-value"><?php echo htmlspecialchars($conversacionActiva['job_title']); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($conversacionActiva['company_name'])): ?>
                            <div class="contact-field">
                                <div class="contact-field-icon"><i class="fas fa-building"></i></div>
                                <div class="contact-field-content">
                                    <div class="contact-field-label">Empresa</div>
                                    <div class="contact-field-value"><?php echo htmlspecialchars($conversacionActiva['company_name']); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="inbox-detail">
                        <div class="empty-state">
                            <i class="fas fa-envelope-open-text"></i>
                            <h3>Selecciona una conversación</h3>
                            <p>Elige una conversación de la lista para ver los mensajes</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script>
        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const isOpen = element.classList.contains('open');
            
            if (isOpen) {
                element.classList.remove('open');
                submenu.classList.remove('open');
            } else {
                element.classList.add('open');
                submenu.classList.add('open');
            }
        }
        
        // Búsqueda en tiempo real
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            document.querySelectorAll('.conversation-item').forEach(item => {
                const name = item.querySelector('.conversation-name')?.textContent.toLowerCase() || '';
                const preview = item.querySelector('.conversation-preview')?.textContent.toLowerCase() || '';
                const subject = item.querySelector('.conversation-subject')?.textContent.toLowerCase() || '';
                
                if (name.includes(query) || preview.includes(query) || subject.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        function openNewMessage() {
            // Aquí abriría un modal para nuevo mensaje
            alert('Función de nuevo mensaje - Por implementar');
        }
        
        // Scroll al último mensaje
        const timeline = document.querySelector('.messages-timeline');
        if (timeline) {
            timeline.scrollTop = timeline.scrollHeight;
        }
    </script>
</body>
</html>