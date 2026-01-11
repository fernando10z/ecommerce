<?php
function getSidebarCounts($conn, $orgId, $userId = null) {
    $counts = [
        'inbox_unread' => 0,
        'tasks_pending' => 0,
        'tickets_open' => 0
    ];
    
    try {
        // INBOX: Conversaciones no leídas (con mensajes entrantes sin responder)
        $stmtInbox = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM inbox_conversations 
            WHERE organization_id = :org_id 
            AND is_read = 0 
            AND status NOT IN ('closed', 'resolved')
        ");
        $stmtInbox->bindParam(':org_id', $orgId, PDO::PARAM_INT);
        $stmtInbox->execute();
        $counts['inbox_unread'] = (int) $stmtInbox->fetchColumn();
        
        // ACTIVIDADES: Tareas pendientes del usuario
        if ($userId) {
            $stmtTasks = $conn->prepare("
                SELECT COUNT(*) as total
                FROM activities a
                WHERE a.organization_id = :org_id
                  AND (a.owner_id = :user_id OR a.assigned_to_id = :user_id2)
                  AND a.status IN ('not_started', 'in_progress')
                  AND a.deleted_at IS NULL
            ");
            $stmtTasks->bindParam(':org_id', $orgId, PDO::PARAM_INT);
            $stmtTasks->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmtTasks->bindParam(':user_id2', $userId, PDO::PARAM_INT);
            $stmtTasks->execute();
            $counts['tasks_pending'] = (int) $stmtTasks->fetchColumn();
        }
        
        // TICKETS: Tickets abiertos/pendientes
        $stmtTickets = $conn->prepare("
            SELECT COUNT(*) as total
            FROM tickets t
            LEFT JOIN ticket_statuses ts ON t.status_id = ts.id
            WHERE t.organization_id = :org_id
              AND (ts.category IN ('new', 'open', 'pending') OR t.status_id IS NULL)
              AND t.deleted_at IS NULL
        ");
        $stmtTickets->bindParam(':org_id', $orgId, PDO::PARAM_INT);
        $stmtTickets->execute();
        $counts['tickets_open'] = (int) $stmtTickets->fetchColumn();
        
    } catch (PDOException $e) {
        error_log("Error en getSidebarCounts: " . $e->getMessage());
    }
    
    return $counts;
}

/**
 * Función auxiliar para mostrar badge solo si hay conteo
 */
function renderBadge($count, $type = 'default') {
    if ($count <= 0) {
        return '';
    }
    
    $class = 'nav-badge';
    if ($type === 'warning') {
        $class .= ' warning';
    }
    
    $display = $count > 99 ? '99+' : $count;
    
    return '<span class="' . $class . '">' . $display . '</span>';
}