<?php
// includes/auth-logic.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CARGAMOS LA CONEXIÓN AQUÍ
// Usamos __DIR__ para que no importe desde dónde se llame el archivo
require_once __DIR__ . '/../System/conexion/conexion.php'; 

$auth_message = '';
$auth_type = '';

// --- LÓGICA DE REGISTRO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Verificar si existe el correo
    // Asumimos que $conn existe porque se incluyó en index.php
    $stmt = $conn->prepare("SELECT id FROM client_accounts WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $auth_message = "Este correo ya está registrado.";
        $auth_type = 'error';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO client_accounts (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$first_name, $last_name, $email, $hashed_password])) {
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['user_name'] = $first_name;
            $_SESSION['user_email'] = $email;
            
            // Redirección limpia
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $auth_message = "Error en el sistema.";
            $auth_type = 'error';
        }
    }
}

// --- LÓGICA DE LOGIN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM client_accounts WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        $_SESSION['user_email'] = $user['email'];
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $auth_message = "Credenciales incorrectas.";
        $auth_type = 'error';
    }
}

// --- LOGOUT ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>