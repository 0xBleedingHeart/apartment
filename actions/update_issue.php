<?php
require_once '../config/init.php';
checkAdmin();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $issue_id = intval($_GET['id']);
    $status = sanitizeInput($_GET['status']);
    
    $completed_at = $status === 'completed' ? 'NOW()' : 'NULL';
    
    $stmt = $pdo->prepare("UPDATE maintenance SET status = ?, completed_at = $completed_at WHERE id = ?");
    $stmt->execute([$status, $issue_id]);
    
    header('Location: ../admin/maintenance.php');
}
?>
