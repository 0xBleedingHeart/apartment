<?php
require_once '../config/init.php';
checkAdmin();

$stmt = $pdo->query("SELECT r.*, a.title, u.first_name, u.last_name FROM reservations r JOIN apartments a ON r.apartment_id = a.id JOIN users u ON r.customer_id = u.id ORDER BY r.created_at DESC");
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations - ApartmentHub Admin</title>
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
                    <a href="reservations.php" class="list-group-item list-group-item-action active">
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
                <div class="text-center mb-4">
                    <h1 class="hero-title">Manage Reservations</h1>
                    <p class="text-white-50">View and manage customer bookings</p>
                </div>
                
                <div class="card glass border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0 text-white">Customer</th>
                                        <th class="border-0 text-white">Apartment</th>
                                        <th class="border-0 text-white">Check-in</th>
                                        <th class="border-0 text-white">Check-out</th>
                                        <th class="border-0 text-white">Guests</th>
                                        <th class="border-0 text-white">Total</th>
                                        <th class="border-0 text-white">Payment</th>
                                        <th class="border-0 text-white">Status</th>
                                        <th class="border-0 text-white">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservations as $reservation): ?>
                                    <tr>
                                        <td class="text-white fw-bold"><?= $reservation['first_name'] . ' ' . $reservation['last_name'] ?></td>
                                        <td class="text-white-50"><?= $reservation['title'] ?></td>
                                        <td class="text-white"><?= date('M d, Y', strtotime($reservation['check_in'])) ?></td>
                                        <td class="text-white"><?= date('M d, Y', strtotime($reservation['check_out'])) ?></td>
                                        <td class="text-white"><?= $reservation['guests'] ?></td>
                                        <td class="text-white fw-bold"><?= formatCurrency($reservation['total_amount']) ?></td>
                                        <td>
                                            <?php if ($reservation['payment_method']): ?>
                                                <small class="text-white-50"><?= ucfirst(str_replace('_', ' ', $reservation['payment_method'])) ?></small><br>
                                                <span class="badge bg-<?= $reservation['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                                                    <?= ucfirst($reservation['payment_status']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-white-50">No payment</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $reservation['status'] === 'confirmed' ? 'success' : ($reservation['status'] === 'pending' ? 'warning' : ($reservation['status'] === 'cancelled' ? 'danger' : 'info')) ?>">
                                                <?= ucfirst($reservation['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($reservation['status'] === 'pending'): ?>
                                                <a href="../actions/update_reservation.php?id=<?= $reservation['id'] ?>&status=confirmed" class="btn btn-sm btn-success me-1">
                                                    <i class="fas fa-check"></i> Confirm
                                                </a>
                                                <a href="../actions/update_reservation.php?id=<?= $reservation['id'] ?>&status=cancelled" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Cancel
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
