<?php
require_once 'config/init.php';

// Get search parameters
$check_in = $_GET['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? '';
$guests = $_GET['guests'] ?? 1;

// Build query for available apartments
$where_conditions = ["status = 'available'"];
$params = [];

if ($check_in && $check_out) {
    $where_conditions[] = "id NOT IN (
        SELECT apartment_id FROM reservations 
        WHERE status IN ('confirmed', 'pending') 
        AND ((check_in <= ? AND check_out > ?) OR (check_in < ? AND check_out >= ?))
    )";
    $params = [$check_in, $check_in, $check_out, $check_out];
}

if ($guests > 1) {
    $where_conditions[] = "max_guests >= ?";
    $params[] = $guests;
}

$where_clause = implode(' AND ', $where_conditions);
$stmt = $pdo->prepare("SELECT * FROM apartments WHERE $where_clause ORDER BY created_at DESC");
$stmt->execute($params);
$apartments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Reservations - Find Your Perfect Stay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-building"></i> ApartmentHub
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-circle"></i> Welcome, <?= $_SESSION['username'] ?>
                    </span>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a class="nav-link" href="admin/dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Admin Panel
                        </a>
                    <?php else: ?>
                        <a class="nav-link" href="customer/reservations.php">
                            <i class="fas fa-calendar-check"></i> My Reservations
                        </a>
                    <?php endif; ?>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a class="nav-link" href="register.php">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="hero-section" style="padding-top: 10rem;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="hero-title text-center mb-3">Find Your Perfect Apartment</h1>
                <p class="text-center text-white mb-4 fs-5">Discover amazing places to stay with modern amenities and unbeatable locations</p>
                
                <div class="search-form glass">
                    <form method="GET" class="row g-4">
                        <div class="col-md-3">
                            <label class="form-label text-white">
                                <i class="fas fa-calendar-alt"></i> Check-in
                            </label>
                            <input type="date" name="check_in" class="form-control" value="<?= $check_in ?>" min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white">
                                <i class="fas fa-calendar-alt"></i> Check-out
                            </label>
                            <input type="date" name="check_out" class="form-control" value="<?= $check_out ?>" min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white">
                                <i class="fas fa-users"></i> Guests
                            </label>
                            <select name="guests" class="form-select">
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?= $i ?>" <?= $guests == $i ? 'selected' : '' ?>>
                                        <?= $i ?> Guest<?= $i > 1 ? 's' : '' ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Search Apartments
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <?php foreach ($apartments as $apartment): ?>
            <div class="apartment-list-item glass border-0 mb-4 fade-in">
                <div class="row g-0 align-items-center">
                    <div class="col-md-3">
                        <div class="apartment-image position-relative" style="height: 200px; border-radius: 15px; overflow: hidden;">
                            <?php if ($apartment['images']): ?>
                                <img src="assets/img/<?= $apartment['images'] ?>" alt="<?= $apartment['title'] ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; background: var(--gradient);"></div>
                            <?php endif; ?>
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Available
                                </span>
                            </div>
                            <div class="position-absolute bottom-0 start-0 m-3">
                                <h6 class="text-white fw-bold"><?= formatCurrency($apartment['price_per_night']) ?></h6>
                                <small class="text-white-50">per night</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="apartment-content p-4">
                            <h4 class="text-white fw-bold mb-2"><?= $apartment['title'] ?></h4>
                            <p class="text-white-50 mb-3">
                                <i class="fas fa-map-marker-alt"></i> <?= substr($apartment['address'], 0, 50) ?>...
                            </p>
                            <p class="text-white-50 mb-3"><?= substr($apartment['description'], 0, 120) ?>...</p>
                            
                            <div class="apartment-features d-flex gap-4">
                                <span class="text-white-50">
                                    <i class="fas fa-bed text-primary"></i> <?= $apartment['bedrooms'] ?> bed
                                </span>
                                <span class="text-white-50">
                                    <i class="fas fa-bath text-primary"></i> <?= $apartment['bathrooms'] ?> bath
                                </span>
                                <span class="text-white-50">
                                    <i class="fas fa-users text-primary"></i> <?= $apartment['max_guests'] ?> guests
                                </span>
                                <span class="text-white-50">
                                    <i class="fas fa-expand-arrows-alt text-primary"></i> <?= $apartment['area_sqm'] ?> sqm
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="apartment-actions p-4 text-center">
                            <div class="mb-3">
                                <div class="rating-display mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="text-warning">★</span>
                                    <?php endfor; ?>
                                    <small class="text-white-50 ms-2">4.8 (24 reviews)</small>
                                </div>
                            </div>
                            <a href="apartment.php?id=<?= $apartment['id'] ?>&check_in=<?= $check_in ?>&check_out=<?= $check_out ?>&guests=<?= $guests ?>" 
                               class="btn btn-primary w-100">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <?php if (empty($apartments)): ?>
        <div class="text-center py-5">
            <div class="glass p-5 rounded-4 d-inline-block">
                <i class="fas fa-search fa-3x text-white mb-3"></i>
                <h4 class="text-white">No apartments available</h4>
                <p class="text-white-50">Try adjusting your search criteria</p>
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-refresh"></i> Reset Search
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
