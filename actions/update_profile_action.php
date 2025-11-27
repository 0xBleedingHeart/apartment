<?php
require_once '../config/init.php';

if ($_POST) {
    $user_id = $_SESSION['user_id'];
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $username = sanitizeInput($_POST['username']);
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, username = ?, password = ? WHERE id = ?");
        $stmt->execute([$first_name, $last_name, $email, $phone, $username, $password, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, username = ? WHERE id = ?");
        $stmt->execute([$first_name, $last_name, $email, $phone, $username, $user_id]);
    }
    
    $_SESSION['username'] = $username;
    
    $redirect = $_SESSION['role'] === 'admin' ? '../admin/profile.php' : '../tenant/profile.php';
    header("Location: $redirect?success=Profile updated successfully");
}
?>
