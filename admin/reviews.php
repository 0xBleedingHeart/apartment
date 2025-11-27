<?php
require_once '../config/init.php';
checkAdmin();

$stmt = $pdo->query("SELECT r.*, a.title, u.first_name, u.last_name FROM reviews r JOIN apartments a ON r.apartment_id = a.id JOIN users u ON r.customer_id = u.id ORDER BY r.created_at DESC");
$reviews = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews - ApartmentHub Admin</title>
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
                    <a href="dashboard.php" class="list-group-item list-group-item-action">
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
                    <a href="reviews.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-star"></i> Reviews
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-10">
            <div class="container mt-4">
                <div class="text-center mb-4">
                    <h1 class="hero-title">Manage Reviews</h1>
                    <p class="text-white-50">Monitor customer feedback and ratings</p>
                </div>
                
                <div class="row">
                    <?php if ($reviews): ?>
                        <?php foreach ($reviews as $review): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card glass border-0">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="text-white fw-bold mb-1"><?= $review['first_name'] . ' ' . $review['last_name'] ?></h6>
                                            <small class="text-white-50"><?= $review['title'] ?></small>
                                        </div>
                                        <div class="text-end">
                                            <div class="mb-1">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <span class="text-<?= $i <= $review['rating'] ? 'warning' : 'muted' ?>">★</span>
                                                <?php endfor; ?>
                                            </div>
                                            <small class="text-white-50"><?= date('M d, Y', strtotime($review['created_at'])) ?></small>
                                        </div>
                                    </div>
                                    <p class="text-white-50 mb-0"><?= $review['comment'] ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="glass p-5 rounded-4 d-inline-block">
                                    <i class="fas fa-star fa-3x text-white mb-3"></i>
                                    <h4 class="text-white">No reviews yet</h4>
                                    <p class="text-white-50">Customer reviews will appear here</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
