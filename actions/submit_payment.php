<?php
require_once '../config/init.php';
checkTenant();

if ($_POST) {
    $tenant_id = $_SESSION['user_id'];
    $amount = floatval($_POST['amount']);
    $payment_method = sanitizeInput($_POST['payment_method']);
    $payment_date = $_POST['payment_date'];
    $notes = sanitizeInput($_POST['notes']);
    
    $receipt_name = null;
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === 0) {
        $upload_dir = '../assets/uploads/receipts/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $receipt_name = time() . '_' . $_FILES['receipt']['name'];
        move_uploaded_file($_FILES['receipt']['tmp_name'], $upload_dir . $receipt_name);
    }
    
    $stmt = $pdo->prepare("INSERT INTO payments (tenant_id, amount, payment_method, payment_date, receipt, notes, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$tenant_id, $amount, $payment_method, $payment_date, $receipt_name, $notes]);
    
    header('Location: ../tenant/payment_history.php?success=Payment submitted successfully');
}
?>
