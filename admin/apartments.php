<?php
require_once '../config/init.php';
checkAdmin();

$stmt = $pdo->query("SELECT * FROM apartments ORDER BY created_at DESC");
$apartments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Apartments - ApartmentHub Admin</title>
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
                    <a href="apartments.php" class="list-group-item list-group-item-action active">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="hero-title mb-2">Manage Apartments</h1>
                        <p class="text-white-50">Add, edit, and manage apartment listings</p>
                    </div>
                    <a href="add_apartment.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Apartment
                    </a>
                </div>
                
                <div class="card glass border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0 text-white">Title</th>
                                        <th class="border-0 text-white">Address</th>
                                        <th class="border-0 text-white">Bedrooms</th>
                                        <th class="border-0 text-white">Price/Night</th>
                                        <th class="border-0 text-white">Status</th>
                                        <th class="border-0 text-white">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($apartments as $apartment): ?>
                                    <tr>
                                        <td class="text-white fw-bold"><?= $apartment['title'] ?></td>
                                        <td class="text-white-50"><?= substr($apartment['address'], 0, 50) ?>...</td>
                                        <td class="text-white"><?= $apartment['bedrooms'] ?></td>
                                        <td class="text-white fw-bold"><?= formatCurrency($apartment['price_per_night']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $apartment['status'] === 'available' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($apartment['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit_apartment.php?id=<?= $apartment['id'] ?>" class="btn btn-sm btn-warning me-2">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="../actions/delete_apartment.php?id=<?= $apartment['id'] ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Delete this apartment?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
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
