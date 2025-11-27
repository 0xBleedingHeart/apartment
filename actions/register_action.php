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
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, first_name, last_name, email, phone) VALUES (?, ?, 'customer', ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $first_name, $last_name, $email, $phone]);
        
        // Auto login after registration
        $user_id = $pdo->lastInsertId();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'customer';
        
        header('Location: ../index.php');
    } catch (PDOException $e) {
        header('Location: ../register.php?error=Username or email already exists');
    }
}
?>
