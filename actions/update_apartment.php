<?php
require_once '../config/init.php';
checkAdmin();

if ($_POST) {
    $apartment_id = intval($_POST['apartment_id']);
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $address = sanitizeInput($_POST['address']);
    $bedrooms = intval($_POST['bedrooms']);
    $bathrooms = intval($_POST['bathrooms']);
    $area_sqm = intval($_POST['area_sqm']);
    $price_per_night = floatval($_POST['price_per_night']);
    $max_guests = intval($_POST['max_guests']);
    $amenities = sanitizeInput($_POST['amenities']);
    $status = sanitizeInput($_POST['status']);
    
    $stmt = $pdo->prepare("UPDATE apartments SET title = ?, description = ?, address = ?, bedrooms = ?, bathrooms = ?, area_sqm = ?, price_per_night = ?, max_guests = ?, amenities = ?, status = ? WHERE id = ?");
    $stmt->execute([$title, $description, $address, $bedrooms, $bathrooms, $area_sqm, $price_per_night, $max_guests, $amenities, $status, $apartment_id]);
    
    header('Location: ../admin/apartments.php');
}
?>
