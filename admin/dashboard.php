<?php
require_once '../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get statistics
$total_apartments = $pdo->query("SELECT COUNT(*) FROM apartments")->fetchColumn();
$total_customers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$pending_reservations = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total_amount) FROM reservations WHERE status = 'confirmed'")->fetchColumn() ?: 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ApartmentHub</title>
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
                <i class="fas fa-user-shield"></i> Admin: <?= $_SESSION['username'] ?>
            </span>
            <a class="nav-link" href="../logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="sidebar mt-4">
                <div class="list-group list-group-flush">
                    <a href="dashboard.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="apartments.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-building"></i> Apartments
                    </a>
                    <a href="reservations.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar-check"></i> Reservations
                    </a>
                    <a href="customers.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-users"></i> Customers
                    </a>
                    <a href="reviews.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-star"></i> Reviews
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-10">
            <div class="container mt-4">
                <div class="text-center mb-5">
                    <h1 class="hero-title">Admin Dashboard</h1>
                    <p class="text-white-50 fs-5">Manage your apartment reservation system</p>
                </div>
                
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="card glass border-0 text-center">
                            <div class="card-body p-4">
                                <i class="fas fa-building fa-2x text-primary mb-3"></i>
                                <h3 class="text-white fw-bold"><?= $total_apartments ?></h3>
                                <p class="text-white-50 mb-0">Total Apartments</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card glass border-0 text-center">
                            <div class="card-body p-4">
                                <i class="fas fa-users fa-2x text-success mb-3"></i>
                                <h3 class="text-white fw-bold"><?= $total_customers ?></h3>
                                <p class="text-white-50 mb-0">Total Customers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card glass border-0 text-center">
                            <div class="card-body p-4">
                                <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                                <h3 class="text-white fw-bold"><?= $pending_reservations ?></h3>
                                <p class="text-white-50 mb-0">Pending Reservations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card glass border-0 text-center">
                            <div class="card-body p-4">
                                <i class="fas fa-money-bill-wave fa-2x text-info mb-3"></i>
                                <h3 class="text-white fw-bold"><?= formatCurrency($total_revenue) ?></h3>
                                <p class="text-white-50 mb-0">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card glass border-0">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="text-white mb-0">
                                    <i class="fas fa-calendar-check"></i> Recent Reservations
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $stmt = $pdo->query("SELECT r.*, a.title, u.first_name, u.last_name FROM reservations r JOIN apartments a ON r.apartment_id = a.id JOIN users u ON r.customer_id = u.id ORDER BY r.created_at DESC LIMIT 5");
                                while ($reservation = $stmt->fetch()): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3" style="background: rgba(255,255,255,0.1);">
                                        <div>
                                            <div class="text-white fw-bold"><?= $reservation['first_name'] . ' ' . $reservation['last_name'] ?></div>
                                            <small class="text-white-50"><?= $reservation['title'] ?></small>
                                        </div>
                                        <span class="badge bg-<?= $reservation['status'] === 'confirmed' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($reservation['status']) ?>
                                        </span>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card glass border-0">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="text-white mb-0">
                                    <i class="fas fa-star"></i> Recent Reviews
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $stmt = $pdo->query("SELECT r.*, a.title, u.first_name, u.last_name FROM reviews r JOIN apartments a ON r.apartment_id = a.id JOIN users u ON r.customer_id = u.id ORDER BY r.created_at DESC LIMIT 5");
                                while ($review = $stmt->fetch()): ?>
                                    <div class="mb-3 p-3 rounded-3" style="background: rgba(255,255,255,0.1);">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="text-white fw-bold"><?= $review['first_name'] . ' ' . $review['last_name'] ?></div>
                                            <div>
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <span class="text-<?= $i <= $review['rating'] ? 'warning' : 'muted' ?>">★</span>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <small class="text-white-50"><?= $review['title'] ?></small>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
