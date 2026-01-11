<?php
header('Content-Type: application/json');
require_once '../../conexion/conexion.php';

try {
    // Si se solicita un contacto específico por ID
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("
            SELECT c.*, co.name as company_name 
            FROM contacts c 
            LEFT JOIN companies co ON c.company_id = co.id 
            WHERE c.id = :id AND c.deleted_at IS NULL
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($contact) {
            echo json_encode(['success' => true, 'contact' => $contact]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Contacto no encontrado']);
        }
        exit;
    }
    
    // Parámetros de paginación y filtros
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $perPage = isset($_GET['per_page']) ? min(100, max(1, (int)$_GET['per_page'])) : 10;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $orgId = 1;
    $offset = ($page - 1) * $perPage;
    
    // Construir WHERE
    $where = "c.organization_id = :org_id AND c.deleted_at IS NULL";
    $params = [':org_id' => $orgId];
    
    if (!empty($search)) {
        $where .= " AND (c.first_name LIKE :search OR c.last_name LIKE :search OR c.email LIKE :search OR c.phone LIKE :search OR c.mobile LIKE :search)";
        $params[':search'] = "%{$search}%";
    }
    
    if (!empty($status)) {
        $where .= " AND c.status = :status";
        $params[':status'] = $status;
    }
    
    // Contar total
    $countSql = "SELECT COUNT(*) FROM contacts c WHERE {$where}";
    $countStmt = $conn->prepare($countSql);
    foreach ($params as $key => $val) {
        $countStmt->bindValue($key, $val);
    }
    $countStmt->execute();
    $total = (int)$countStmt->fetchColumn();
    
    // Obtener contactos
    $sql = "
        SELECT 
            c.id,
            c.uuid,
            c.prefix,
            c.first_name,
            c.middle_name,
            c.last_name,
            c.nickname,
            c.email,
            c.secondary_email,
            c.phone,
            c.mobile,
            c.work_phone,
            c.job_title,
            c.department,
            c.company_id,
            c.lead_source,
            c.lifecycle_stage,
            c.status,
            c.description,
            c.owner_id,
            c.created_at,
            co.name as company_name,
            CONCAT(u.first_name, ' ', u.last_name) as owner_name
        FROM contacts c
        LEFT JOIN companies co ON c.company_id = co.id
        LEFT JOIN users u ON c.owner_id = u.id
        WHERE {$where}
        ORDER BY c.created_at DESC
        LIMIT :limit OFFSET :offset
    ";
    
    $stmt = $conn->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'contacts' => $contacts,
        'pagination' => [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Error en get.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al obtener contactos']);
}