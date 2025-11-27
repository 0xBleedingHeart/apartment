<?php
require_once '../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../login.php');
    exit();
}

if ($_POST) {
    $customer_id = $_SESSION['user_id'];
    $apartment_id = intval($_POST['apartment_id']);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests = intval($_POST['guests']);
    $payment_method = sanitizeInput($_POST['payment_method']);
    $special_requests = sanitizeInput($_POST['special_requests']);
    
    // Calculate nights and total amount
    $checkin_date = new DateTime($check_in);
    $checkout_date = new DateTime($check_out);
    $total_nights = $checkin_date->diff($checkout_date)->days;
    
    // Get apartment price
    $stmt = $pdo->prepare("SELECT price_per_night FROM apartments WHERE id = ?");
    $stmt->execute([$apartment_id]);
    $price_per_night = $stmt->fetchColumn();
    
    $total_amount = $total_nights * $price_per_night;
    
    // Check availability
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE apartment_id = ? AND status IN ('confirmed', 'pending') AND ((check_in <= ? AND check_out > ?) OR (check_in < ? AND check_out >= ?))");
    $stmt->execute([$apartment_id, $check_in, $check_in, $check_out, $check_out]);
    $conflicts = $stmt->fetchColumn();
    
    if ($conflicts > 0) {
        header('Location: ../apartment.php?id=' . $apartment_id . '&error=Apartment not available for selected dates');
        exit();
    }
    
    // Create reservation with payment method and mark as paid
    $stmt = $pdo->prepare("INSERT INTO reservations (customer_id, apartment_id, check_in, check_out, guests, total_nights, total_amount, payment_method, payment_status, payment_date, special_requests) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'paid', NOW(), ?)");
    $stmt->execute([$customer_id, $apartment_id, $check_in, $check_out, $guests, $total_nights, $total_amount, $payment_method, $special_requests]);
    
    header('Location: ../customer/reservations.php?success=Reservation created and payment processed successfully');
}
?>
