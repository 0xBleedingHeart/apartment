<?php
require_once '../config/init.php';
checkAdmin();

if ($_POST) {
    $tenant_id = intval($_POST['tenant_id']);
    $room_id = intval($_POST['room_id']);
    $start_date = $_POST['start_date'];
    $monthly_rent = floatval($_POST['monthly_rent']);
    $deposit = floatval($_POST['deposit']);
    
    try {
        $pdo->beginTransaction();
        
        // Create contract
        $stmt = $pdo->prepare("INSERT INTO contracts (tenant_id, room_id, start_date, monthly_rent, deposit) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$tenant_id, $room_id, $start_date, $monthly_rent, $deposit]);
        
        // Update room status to occupied
        $stmt = $pdo->prepare("UPDATE rooms SET status = 'occupied' WHERE id = ?");
        $stmt->execute([$room_id]);
        
        $pdo->commit();
        header('Location: ../admin/contracts.php');
    } catch (Exception $e) {
        $pdo->rollback();
        header('Location: ../admin/add_contract.php?error=Failed to create contract');
    }
}
?>
