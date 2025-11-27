<?php
require_once '../config/init.php';
checkAdmin();

if ($_POST) {
    $room_id = intval($_POST['room_id']);
    $room_number = sanitizeInput($_POST['room_number']);
    $room_type = sanitizeInput($_POST['room_type']);
    $price = floatval($_POST['price']);
    $status = sanitizeInput($_POST['status']);
    $description = sanitizeInput($_POST['description']);
    
    $image_name = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../assets/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $image_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
        
        $stmt = $pdo->prepare("UPDATE rooms SET room_number = ?, room_type = ?, price = ?, status = ?, description = ?, image = ? WHERE id = ?");
        $stmt->execute([$room_number, $room_type, $price, $status, $description, $image_name, $room_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE rooms SET room_number = ?, room_type = ?, price = ?, status = ?, description = ? WHERE id = ?");
        $stmt->execute([$room_number, $room_type, $price, $status, $description, $room_id]);
    }
    
    header('Location: ../admin/rooms.php');
}
?>
