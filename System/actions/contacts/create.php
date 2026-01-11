<?php
header('Content-Type: application/json');
require_once '../../conexion/conexion.php';

try {
    // ═══════════════════════════════════════════════════════════════
    // VALIDACIONES
    // ═══════════════════════════════════════════════════════════════
    
    // Campos requeridos
    if (empty($_POST['first_name']) || empty($_POST['last_name'])) {
        echo json_encode(['success' => false, 'message' => 'Nombre y apellido son requeridos']);
        exit;
    }
    
    // Sanitizar y validar datos
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    
    // Validar nombres (solo letras)
    if (!preg_match('/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]{2,50}$/', $firstName)) {
        echo json_encode(['success' => false, 'message' => 'Nombre inválido. Solo letras, 2-50 caracteres']);
        exit;
    }
    
    if (!preg_match('/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]{2,50}$/', $lastName)) {
        echo json_encode(['success' => false, 'message' => 'Apellido inválido. Solo letras, 2-50 caracteres']);
        exit;
    }
    
    // Validar email si se proporciona
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email principal inválido']);
        exit;
    }
    
    $secondaryEmail = !empty($_POST['secondary_email']) ? trim($_POST['secondary_email']) : null;
    if ($secondaryEmail && !filter_var($secondaryEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email secundario inválido']);
        exit;
    }
    
    // Validar teléfonos (solo números, 7-9 dígitos)
    $phone = !empty($_POST['phone']) ? preg_replace('/[^0-9]/', '', $_POST['phone']) : null;
    if ($phone && !preg_match('/^[0-9]{7,9}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Teléfono inválido. 7-9 dígitos']);
        exit;
    }
    
    $mobile = !empty($_POST['mobile']) ? preg_replace('/[^0-9]/', '', $_POST['mobile']) : null;
    if ($mobile && !preg_match('/^[0-9]{9}$/', $mobile)) {
        echo json_encode(['success' => false, 'message' => 'Celular inválido. Exactamente 9 dígitos']);
        exit;
    }
    
    $workPhone = !empty($_POST['work_phone']) ? preg_replace('/[^0-9]/', '', $_POST['work_phone']) : null;
    if ($workPhone && !preg_match('/^[0-9]{7,9}$/', $workPhone)) {
        echo json_encode(['success' => false, 'message' => 'Teléfono de trabajo inválido. 7-9 dígitos']);
        exit;
    }
    
    // ═══════════════════════════════════════════════════════════════
    // PREPARAR DATOS
    // ═══════════════════════════════════════════════════════════════
    
    $orgId = 1;
    $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
    
    $prefix = !empty($_POST['prefix']) ? trim($_POST['prefix']) : null;
    $middleName = !empty($_POST['middle_name']) ? trim($_POST['middle_name']) : null;
    $nickname = !empty($_POST['nickname']) ? trim($_POST['nickname']) : null;
    $companyId = !empty($_POST['company_id']) ? (int)$_POST['company_id'] : null;
    $jobTitle = !empty($_POST['job_title']) ? trim($_POST['job_title']) : null;
    $department = !empty($_POST['department']) ? trim($_POST['department']) : null;
    $leadSource = !empty($_POST['lead_source']) ? trim($_POST['lead_source']) : null;
    $lifecycleStage = !empty($_POST['lifecycle_stage']) ? trim($_POST['lifecycle_stage']) : 'lead';
    $status = !empty($_POST['status']) ? trim($_POST['status']) : 'active';
    $ownerId = !empty($_POST['owner_id']) ? (int)$_POST['owner_id'] : null;
    $description = !empty($_POST['description']) ? trim($_POST['description']) : null;
    
    // Limitar descripción a 1000 caracteres
    if ($description && strlen($description) > 1000) {
        $description = substr($description, 0, 1000);
    }
    
    // ═══════════════════════════════════════════════════════════════
    // INSERTAR EN BD
    // ═══════════════════════════════════════════════════════════════
    
    $sql = "INSERT INTO contacts (
        organization_id, uuid, prefix, first_name, middle_name, last_name, nickname,
        email, secondary_email, phone, mobile, work_phone,
        company_id, job_title, department, lead_source, lifecycle_stage,
        status, owner_id, description, created_at
    ) VALUES (
        :org_id, :uuid, :prefix, :first_name, :middle_name, :last_name, :nickname,
        :email, :secondary_email, :phone, :mobile, :work_phone,
        :company_id, :job_title, :department, :lead_source, :lifecycle_stage,
        :status, :owner_id, :description, NOW()
    )";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':org_id', $orgId, PDO::PARAM_INT);
    $stmt->bindParam(':uuid', $uuid);
    $stmt->bindParam(':prefix', $prefix);
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':middle_name', $middleName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':nickname', $nickname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':secondary_email', $secondaryEmail);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':work_phone', $workPhone);
    $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
    $stmt->bindParam(':job_title', $jobTitle);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':lead_source', $leadSource);
    $stmt->bindParam(':lifecycle_stage', $lifecycleStage);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':owner_id', $ownerId, PDO::PARAM_INT);
    $stmt->bindParam(':description', $description);
    
    $stmt->execute();
    $newId = $conn->lastInsertId();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Contacto creado exitosamente',
        'id' => $newId
    ]);
    
} catch (PDOException $e) {
    error_log("Error en create.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al crear el contacto: ' . $e->getMessage()]);
}