<?php
require_once '../config/init.php';
checkCustomer();

if ($_POST) {
    $customer_id = $_SESSION['user_id'];
    $apartment_id = intval($_POST['apartment_id']);
    $rating = intval($_POST['rating']);
    $comment = sanitizeInput($_POST['comment']);
    
    // Check if customer has a completed reservation for this apartment
    $stmt = $pdo->prepare("SELECT id FROM reservations WHERE customer_id = ? AND apartment_id = ? AND status = 'completed' LIMIT 1");
    $stmt->execute([$customer_id, $apartment_id]);
    $reservation = $stmt->fetch();
    
    if ($reservation) {
        $reservation_id = $reservation['id'];
        
        // Check if review already exists for this customer and apartment
        $stmt = $pdo->prepare("SELECT id FROM reviews WHERE customer_id = ? AND apartment_id = ?");
        $stmt->execute([$customer_id, $apartment_id]);
        
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO reviews (customer_id, apartment_id, reservation_id, rating, comment) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$customer_id, $apartment_id, $reservation_id, $rating, $comment]);
            
            header('Location: ../apartment.php?id=' . $apartment_id . '&success=Review submitted successfully');
        } else {
            header('Location: ../apartment.php?id=' . $apartment_id . '&error=You have already reviewed this apartment');
        }
    } else {
        header('Location: ../apartment.php?id=' . $apartment_id . '&error=You must complete a stay to review this apartment');
    }
}
?>
