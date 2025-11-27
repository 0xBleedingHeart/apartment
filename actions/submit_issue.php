<?php
require_once '../config/init.php';
checkTenant();

if ($_POST) {
    $tenant_id = $_SESSION['user_id'];
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $priority = sanitizeInput($_POST['priority']);
    $room_id = isset($_POST['room_id']) ? intval($_POST['room_id']) : null;
    
    $stmt = $pdo->prepare("INSERT INTO maintenance (tenant_id, room_id, title, description, priority) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$tenant_id, $room_id, $title, $description, $priority]);
    
    header('Location: ../tenant/dashboard.php?success=Issue reported successfully');
}
?>
