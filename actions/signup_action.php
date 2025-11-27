<?php
require_once '../config/init.php';

if ($_POST) {
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $username = sanitizeInput($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, first_name, last_name, email, phone, status) VALUES (?, ?, 'tenant', ?, ?, ?, ?, 'pending')");
        $stmt->execute([$username, $password, $first_name, $last_name, $email, $phone]);
        
        header('Location: ../index.php?success=Registration successful. Please wait for admin approval.');
    } catch (PDOException $e) {
        header('Location: ../signup.php?error=Username already exists');
    }
}
?>
