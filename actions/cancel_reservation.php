<?php
require_once '../config/init.php';
checkCustomer();

if (isset($_GET['id'])) {
    $reservation_id = intval($_GET['id']);
    $customer_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ? AND customer_id = ?");
    $stmt->execute([$reservation_id, $customer_id]);
    
    header('Location: ../customer/reservations.php');
}
?>
