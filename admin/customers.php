<?php
require_once '../config/init.php';
checkAdmin();

$stmt = $pdo->query("SELECT u.*, COUNT(r.id) as total_reservations FROM users u LEFT JOIN reservations r ON u.id = r.customer_id WHERE u.role = 'customer' GROUP BY u.id ORDER BY u.created_at DESC");
$customers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers - ApartmentHub Admin</title>
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
                    <a href="customers.php" class="list-group-item list-group-item-action active">
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
                    <h1 class="hero-title">Manage Customers</h1>
                    <p class="text-white-50">View customer information and statistics</p>
                </div>
                
                <div class="card glass border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0 text-white">Name</th>
                                        <th class="border-0 text-white">Email</th>
                                        <th class="border-0 text-white">Phone</th>
                                        <th class="border-0 text-white">Total Reservations</th>
                                        <th class="border-0 text-white">Joined</th>
                                        <th class="border-0 text-white">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $customer): ?>
                                    <tr>
                                        <td class="text-white fw-bold"><?= $customer['first_name'] . ' ' . $customer['last_name'] ?></td>
                                        <td class="text-white-50"><?= $customer['email'] ?></td>
                                        <td class="text-white-50"><?= $customer['phone'] ?></td>
                                        <td class="text-white fw-bold"><?= $customer['total_reservations'] ?></td>
                                        <td class="text-white"><?= date('M d, Y', strtotime($customer['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $customer['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($customer['status']) ?>
                                            </span>
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
