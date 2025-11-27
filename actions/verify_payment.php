<?php
require_once '../config/init.php';
checkAdmin();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $payment_id = intval($_GET['id']);
    $status = sanitizeInput($_GET['status']);
    
    $stmt = $pdo->prepare("UPDATE payments SET status = ?, verified_at = NOW() WHERE id = ?");
    $stmt->execute([$status, $payment_id]);
    
    header('Location: ../admin/payments.php');
}
?>
