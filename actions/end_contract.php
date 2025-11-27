<?php
require_once '../config/init.php';
checkAdmin();

if (isset($_GET['id'])) {
    $contract_id = intval($_GET['id']);
    
    try {
        $pdo->beginTransaction();
        
        // Get room ID from contract
        $stmt = $pdo->prepare("SELECT room_id FROM contracts WHERE id = ?");
        $stmt->execute([$contract_id]);
        $room_id = $stmt->fetchColumn();
        
        // End contract
        $stmt = $pdo->prepare("UPDATE contracts SET status = 'ended', end_date = CURDATE() WHERE id = ?");
        $stmt->execute([$contract_id]);
        
        // Update room status to available
        $stmt = $pdo->prepare("UPDATE rooms SET status = 'available' WHERE id = ?");
        $stmt->execute([$room_id]);
        
        $pdo->commit();
        header('Location: ../admin/contracts.php');
    } catch (Exception $e) {
        $pdo->rollback();
        header('Location: ../admin/contracts.php?error=Failed to end contract');
    }
}
?>
