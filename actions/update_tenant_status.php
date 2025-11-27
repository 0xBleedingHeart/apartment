<?php
require_once '../config/init.php';
checkAdmin();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $tenant_id = intval($_GET['id']);
    $status = sanitizeInput($_GET['status']);
    
    $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'tenant'");
    $stmt->execute([$status, $tenant_id]);
    
    header('Location: ../admin/tenants.php');
}
?>
