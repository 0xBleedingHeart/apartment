<?php
require_once '../config/init.php';
checkAdmin();

if (isset($_GET['id'])) {
    $apartment_id = intval($_GET['id']);
    
    // Check if apartment has active reservations
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE apartment_id = ? AND status IN ('confirmed', 'pending')");
    $stmt->execute([$apartment_id]);
    $active_reservations = $stmt->fetchColumn();
    
    if ($active_reservations > 0) {
        header('Location: ../admin/apartments.php?error=Cannot delete apartment with active reservations');
    } else {
        $stmt = $pdo->prepare("DELETE FROM apartments WHERE id = ?");
        $stmt->execute([$apartment_id]);
        header('Location: ../admin/apartments.php');
    }
}
?>
