<?php
$host = "localhost";      
$dbname = "ecommerce_crm";
$username = "root";        
$password = "";           

try {
    // Crear conexión con PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar para mostrar errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>