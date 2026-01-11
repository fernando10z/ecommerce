<?php

require_once '../../conexion/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../inbox.php');
    exit;
}

$conversationId = (int)$_POST['conversation_id'];
$message = trim($_POST['message']);
$userId = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['user_name'] ?? 'Usuario';
$userEmail = $_SESSION['user_email'] ?? 'usuario@sistema.com';

if (empty($message)) {
    header('Location: ../../inbox.php?conv=' . $conversationId . '&error=empty');
    exit;
}

try {
    $conn->beginTransaction();
    
    // 1. Insertar mensaje saliente
    $stmt = $conn->prepare("
        INSERT INTO inbox_messages 
        (conversation_id, direction, sender_type, sender_id, sender_name, sender_email, content, message_type, is_read)
        VALUES 
        (:conv_id, 'outbound', 'user', :user_id, :user_name, :user_email, :content, 'text', 1)
    ");
    $stmt->execute([
        ':conv_id' => $conversationId,
        ':user_id' => $userId,
        ':user_name' => $userName,
        ':user_email' => $userEmail,
        ':content' => $message
    ]);
    
    // 2. Actualizar conversación
    $stmt = $conn->prepare("
        UPDATE inbox_conversations 
        SET last_message_preview = :preview,
            last_message_at = NOW(),
            status = 'pending',
            updated_at = NOW()
        WHERE id = :conv_id
    ");
    $stmt->execute([
        ':preview' => substr($message, 0, 200),
        ':conv_id' => $conversationId
    ]);
    
    // 3. Obtener datos del contacto para enviar el mensaje real
    $stmt = $conn->prepare("
        SELECT ic.channel, c.email, c.phone, c.mobile
        FROM inbox_conversations ic
        JOIN contacts c ON ic.contact_id = c.id
        WHERE ic.id = :conv_id
    ");
    $stmt->execute([':conv_id' => $conversationId]);
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 4. Enviar mensaje según el canal
    switch ($datos['channel']) {
        case 'email':
            // enviarEmail($datos['email'], $message);
            break;
        case 'whatsapp':
            // enviarWhatsApp($datos['phone'] ?? $datos['mobile'], $message);
            break;
        case 'sms':
            // enviarSMS($datos['phone'] ?? $datos['mobile'], $message);
            break;
    }
    
    $conn->commit();
    header('Location: ../../inbox.php?conv=' . $conversationId . '&sent=1');
    
} catch (Exception $e) {
    $conn->rollBack();
    header('Location: ../../inbox.php?conv=' . $conversationId . '&error=' . urlencode($e->getMessage()));
}