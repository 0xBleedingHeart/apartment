<?php
require_once '../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../login.php');
    exit();
}

$customer_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT r.*, a.title, a.address FROM reservations r JOIN apartments a ON r.apartment_id = a.id WHERE r.customer_id = ? ORDER BY r.created_at DESC");
$stmt->execute([$customer_id]);
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - ApartmentHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <i class="fas fa-building"></i> ApartmentHub
        </a>
        <div class="navbar-nav ms-auto">
            <span class="navbar-text me-3">
                <i class="fas fa-user-circle"></i> Welcome, <?= $_SESSION['username'] ?>
            </span>
            <a class="nav-link" href="reservations.php">
                <i class="fas fa-calendar-check"></i> My Reservations
            </a>
            <a class="nav-link" href="../logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="hero-title">My Reservations</h1>
        <p class="text-white-50 fs-5">Manage your apartment bookings</p>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success glass border-0 text-white mb-4"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    
    <?php if ($reservations): ?>
        <?php foreach ($reservations as $reservation): ?>
        <div class="card glass border-0 mb-4 fade-in">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="text-white fw-bold mb-2"><?= $reservation['title'] ?></h4>
                        <p class="text-white-50 mb-3">
                            <i class="fas fa-map-marker-alt"></i> <?= $reservation['address'] ?>
                        </p>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <small class="text-white-50">Check-in</small>
                                <div class="text-white fw-bold"><?= date('M d, Y', strtotime($reservation['check_in'])) ?></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-white-50">Check-out</small>
                                <div class="text-white fw-bold"><?= date('M d, Y', strtotime($reservation['check_out'])) ?></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-white-50">Guests</small>
                                <div class="text-white fw-bold"><?= $reservation['guests'] ?></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-white-50">Total</small>
                                <div class="text-white fw-bold"><?= formatCurrency($reservation['total_amount']) ?></div>
                            </div>
                        </div>
                        
                        <?php if ($reservation['payment_method']): ?>
                            <p class="text-white-50 mb-2">
                                <strong>Payment:</strong> <?= ucfirst(str_replace('_', ' ', $reservation['payment_method'])) ?> - 
                                <span class="badge bg-<?= $reservation['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($reservation['payment_status']) ?>
                                </span>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($reservation['special_requests']): ?>
                            <p class="text-white-50 mb-0">
                                <strong>Special Requests:</strong> <?= $reservation['special_requests'] ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-4 text-end">
                        <div class="mb-3">
                            <span class="badge bg-<?= $reservation['status'] === 'confirmed' ? 'success' : ($reservation['status'] === 'pending' ? 'warning' : ($reservation['status'] === 'cancelled' ? 'danger' : 'info')) ?> fs-6 px-3 py-2">
                                <?= ucfirst($reservation['status']) ?>
                            </span>
                        </div>
                        <small class="text-white-50 d-block mb-3">
                            Booked: <?= date('M d, Y', strtotime($reservation['created_at'])) ?>
                        </small>
                        
                        <?php if ($reservation['status'] === 'pending' && $reservation['payment_status'] === 'unpaid'): ?>
                            <a href="../actions/cancel_reservation.php?id=<?= $reservation['id'] ?>" 
                               class="btn btn-outline-danger btn-sm" 
                               onclick="return confirm('Cancel this reservation?')">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        <?php elseif ($reservation['payment_status'] === 'paid'): ?>
                            <small class="text-white-50">Cannot cancel - Payment processed</small>
                        <?php endif; ?>
                        
                        <?php if ($reservation['status'] === 'completed'): ?>
                            <a href="review.php?reservation_id=<?= $reservation['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-star"></i> Write Review
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="glass p-5 rounded-4 d-inline-block">
                <i class="fas fa-calendar-times fa-3x text-white mb-3"></i>
                <h4 class="text-white">No reservations yet</h4>
                <p class="text-white-50">Start exploring apartments to make your first reservation!</p>
                <a href="../index.php" class="btn btn-primary">
                    <i class="fas fa-search"></i> Browse Apartments
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
