<?php
session_start();

// Verificar si el usuario está autenticado
function isAuthenticated() {
    return isset($_SESSION['user']) && isset($_SESSION['user']['id']);
}

// Obtener usuario actual
function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

// Verificar si tiene permiso
function hasPermission($module, $action, $resource = 'all') {
    if (!isset($_SESSION['permissions'])) {
        return false;
    }
    
    foreach ($_SESSION['permissions'] as $perm) {
        if ($perm['module'] === $module && 
            $perm['action'] === $action && 
            ($perm['resource'] === $resource || $perm['resource'] === 'all')) {
            return true;
        }
    }
    
    return false;
}

// Verificar timeout de sesión (30 minutos)
function checkSessionTimeout() {
    $timeout = 1800; // 30 minutos
    
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $timeout)) {
        return false;
    }
    
    return true;
}

// Requerir autenticación
function requireAuth() {
    if (!isAuthenticated() || !checkSessionTimeout()) {
        header('Location: ../index.php');
        exit;
    }
}

// Cerrar sesión
function logout() {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}