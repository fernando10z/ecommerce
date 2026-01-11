<?php
// Este archivo sería llamado por webhooks de email/WhatsApp

require_once '../../conexion/conexion.php';

function recibirMensaje($data) {
    global $conn;
    
    $orgId = 1; // Tu organización
    $channel = $data['channel']; // 'email', 'whatsapp', 'sms'
    $senderEmail = $data['from_email'] ?? null;
    $senderPhone = $data['from_phone'] ?? null;
    $senderName = $data['from_name'];
    $subject = $data['subject'] ?? null;
    $content = $data['content'];
    $contentHtml = $data['content_html'] ?? null;
    
    try {
        $conn->beginTransaction();
        
        // 1. Buscar o crear contacto
        $contactId = buscarOCrearContacto($senderEmail, $senderPhone, $senderName, $orgId);
        
        // 2. Buscar conversación abierta con este contacto y canal
        $stmt = $conn->prepare("
            SELECT id FROM inbox_conversations 
            WHERE contact_id = :contact_id 
            AND channel = :channel 
            AND status IN ('open', 'pending')
            ORDER BY last_message_at DESC 
            LIMIT 1
        ");
        $stmt->execute([':contact_id' => $contactId, ':channel' => $channel]);
        $conversacion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($conversacion) {
            $conversationId = $conversacion['id'];
        } else {
            // 3. Crear nueva conversación
            $stmt = $conn->prepare("
                INSERT INTO inbox_conversations 
                (organization_id, uuid, contact_id, channel, status, priority, subject, last_message_preview, last_message_at, is_read)
                VALUES 
                (:org_id, UUID(), :contact_id, :channel, 'open', 'normal', :subject, :preview, NOW(), 0)
            ");
            $stmt->execute([
                ':org_id' => $orgId,
                ':contact_id' => $contactId,
                ':channel' => $channel,
                ':subject' => $subject,
                ':preview' => substr(strip_tags($content), 0, 200)
            ]);
            $conversationId = $conn->lastInsertId();
        }
        
        // 4. Insertar el mensaje
        $stmt = $conn->prepare("
            INSERT INTO inbox_messages 
            (conversation_id, direction, sender_type, sender_id, sender_name, sender_email, content, content_html, message_type, is_read)
            VALUES 
            (:conv_id, 'inbound', 'contact', :contact_id, :sender_name, :sender_email, :content, :content_html, :msg_type, 0)
        ");
        $stmt->execute([
            ':conv_id' => $conversationId,
            ':contact_id' => $contactId,
            ':sender_name' => $senderName,
            ':sender_email' => $senderEmail ?? $senderPhone,
            ':content' => $content,
            ':content_html' => $contentHtml,
            ':msg_type' => $contentHtml ? 'html' : 'text'
        ]);
        
        // 5. Actualizar conversación
        $stmt = $conn->prepare("
            UPDATE inbox_conversations 
            SET last_message_preview = :preview,
                last_message_at = NOW(),
                is_read = 0,
                updated_at = NOW()
            WHERE id = :conv_id
        ");
        $stmt->execute([
            ':preview' => substr(strip_tags($content), 0, 200),
            ':conv_id' => $conversationId
        ]);
        
        $conn->commit();
        return ['success' => true, 'conversation_id' => $conversationId];
        
    } catch (Exception $e) {
        $conn->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function buscarOCrearContacto($email, $phone, $name, $orgId) {
    global $conn;
    
    // Buscar por email o teléfono
    $stmt = $conn->prepare("
        SELECT id FROM contacts 
        WHERE organization_id = :org_id 
        AND (email = :email OR phone = :phone OR mobile = :phone)
        LIMIT 1
    ");
    $stmt->execute([':org_id' => $orgId, ':email' => $email, ':phone' => $phone]);
    $contacto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($contacto) {
        return $contacto['id'];
    }
    
    // Crear nuevo contacto
    $nombres = explode(' ', $name, 2);
    $firstName = $nombres[0];
    $lastName = $nombres[1] ?? '';
    
    $stmt = $conn->prepare("
        INSERT INTO contacts (organization_id, uuid, first_name, last_name, email, phone, status)
        VALUES (:org_id, UUID(), :first_name, :last_name, :email, :phone, 'active')
    ");
    $stmt->execute([
        ':org_id' => $orgId,
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':email' => $email,
        ':phone' => $phone
    ]);
    
    return $conn->lastInsertId();
}