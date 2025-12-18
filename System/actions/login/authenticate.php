<?php
session_start();
header('Content-Type: application/json');

require_once '../../conexion/conexion.php';

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener datos
$input = json_decode(file_get_contents('php://input'), true);
$email = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $input['password'] ?? '';

// Validar campos
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
    exit;
}

try {
    // Consultar usuario con su rol
    $stmt = $conn->prepare("
        SELECT 
            u.id,
            u.uuid,
            u.email,
            u.password_hash,
            u.first_name,
            u.last_name,
            u.display_name,
            u.avatar_url,
            u.status,
            u.failed_login_attempts,
            u.locked_until,
            u.must_change_password,
            u.organization_id,
            r.name as role_name,
            r.slug as role_slug,
            r.id as role_id
        FROM users u
        INNER JOIN user_roles ur ON u.id = ur.user_id
        INNER JOIN roles r ON ur.role_id = r.id
        WHERE u.email = :email
        AND u.deleted_at IS NULL
        LIMIT 1
    ");
    
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Usuario no existe
    if (!$user) {
        logAccess(null, $email, 'login_failed', 'Usuario no existe');
        echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
        exit;
    }
    
    // Verificar bloqueo temporal
    if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
        $remainingMinutes = ceil((strtotime($user['locked_until']) - time()) / 60);
        logAccess($user['id'], $email, 'login_failed', 'Cuenta bloqueada');
        echo json_encode([
            'success' => false, 
            'message' => "Cuenta bloqueada. Intenta en {$remainingMinutes} minutos"
        ]);
        exit;
    }
    
    // Verificar estado de la cuenta
    if ($user['status'] !== 'active') {
        logAccess($user['id'], $email, 'login_failed', "Estado: {$user['status']}");
        echo json_encode(['success' => false, 'message' => 'Cuenta no disponible']);
        exit;
    }
    
    // Verificar contraseña
    if (!password_verify($password, $user['password_hash'])) {
        incrementFailedAttempts($user['id']);
        logAccess($user['id'], $email, 'login_failed', 'Contraseña incorrecta');
        
        $attempts = $user['failed_login_attempts'] + 1;
        $remaining = 5 - $attempts;
        
        if ($remaining <= 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'Cuenta bloqueada por múltiples intentos fallidos'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => "Contraseña incorrecta. {$remaining} intentos restantes"
            ]);
        }
        exit;
    }
    
    // Login exitoso - Obtener permisos
    $permStmt = $conn->prepare("
        SELECT DISTINCT
            p.module,
            p.action,
            p.resource
        FROM role_permissions rp
        INNER JOIN permissions p ON rp.permission_id = p.id
        WHERE rp.role_id = :role_id
    ");
    
    $permStmt->execute(['role_id' => $user['role_id']]);
    $permissions = $permStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Actualizar último login
    updateLoginSuccess($user['id']);
    logAccess($user['id'], $email, 'login_success', null);
    
    // Crear sesión
    $_SESSION['user'] = [
        'id' => $user['id'],
        'uuid' => $user['uuid'],
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'display_name' => $user['display_name'],
        'avatar_url' => $user['avatar_url'],
        'role_name' => $user['role_name'],
        'role_slug' => $user['role_slug'],
        'organization_id' => $user['organization_id'],
        'must_change_password' => (bool)$user['must_change_password']
    ];
    
    $_SESSION['permissions'] = $permissions;
    $_SESSION['login_time'] = time();
    
    echo json_encode([
        'success' => true,
        'message' => 'Autenticación exitosa',
        'redirect' => $user['must_change_password'] ? 'actions/login/change-password.php' : 'dashboard.php'
    ]);
    
} catch (PDOException $e) {
    error_log("Error de autenticación: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error del sistema']);
}

// ==================== FUNCIONES ====================

function incrementFailedAttempts($userId) {
    global $conn;
    
    $stmt = $conn->prepare("
        UPDATE users 
        SET 
            failed_login_attempts = failed_login_attempts + 1,
            locked_until = CASE 
                WHEN failed_login_attempts >= 4 THEN DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                ELSE locked_until
            END
        WHERE id = :user_id
    ");
    
    $stmt->execute(['user_id' => $userId]);
}

function updateLoginSuccess($userId) {
    global $conn;
    
    $stmt = $conn->prepare("
        UPDATE users 
        SET 
            failed_login_attempts = 0,
            locked_until = NULL,
            last_login_at = NOW(),
            last_login_ip = :ip
        WHERE id = :user_id
    ");
    
    $stmt->execute([
        'user_id' => $userId,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);
}

function logAccess($userId, $email, $action, $failureReason) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("
            INSERT INTO access_logs (
                user_id,
                email_attempted,
                action,
                ip_address,
                user_agent,
                failure_reason
            ) VALUES (
                :user_id,
                :email,
                :action,
                :ip,
                :user_agent,
                :failure_reason
            )
        ");
        
        $stmt->execute([
            'user_id' => $userId,
            'email' => $email,
            'action' => $action,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 500),
            'failure_reason' => $failureReason
        ]);
    } catch (PDOException $e) {
        error_log("Error al registrar access_log: " . $e->getMessage());
    }
}