<?php
require_once '../config/init.php';
checkAdmin();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $reservation_id = intval($_GET['id']);
    $status = sanitizeInput($_GET['status']);
    
    $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    $stmt->execute([$status, $reservation_id]);
    
    header('Location: ../admin/reservations.php');
}
?>
