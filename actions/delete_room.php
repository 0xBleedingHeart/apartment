<?php
require_once '../config/init.php';
checkAdmin();

if (isset($_GET['id'])) {
    $room_id = intval($_GET['id']);
    
    // Check if room has active contracts
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM contracts WHERE room_id = ? AND status = 'active'");
    $stmt->execute([$room_id]);
    $active_contracts = $stmt->fetchColumn();
    
    if ($active_contracts > 0) {
        header('Location: ../admin/rooms.php?error=Cannot delete room with active contracts');
    } else {
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->execute([$room_id]);
        header('Location: ../admin/rooms.php');
    }
}
?>
