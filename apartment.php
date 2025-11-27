<?php
require_once 'config/init.php';

$apartment_id = intval($_GET['id']);
$check_in = $_GET['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? '';
$guests = $_GET['guests'] ?? 1;

$stmt = $pdo->prepare("SELECT * FROM apartments WHERE id = ?");
$stmt->execute([$apartment_id]);
$apartment = $stmt->fetch();

if (!$apartment) {
    header('Location: index.php');
    exit();
}

// Calculate total nights and amount
$total_nights = 0;
$total_amount = 0;
if ($check_in && $check_out) {
    $checkin_date = new DateTime($check_in);
    $checkout_date = new DateTime($check_out);
    $total_nights = $checkin_date->diff($checkout_date)->days;
    $total_amount = $total_nights * $apartment['price_per_night'];
}

// Get reviews
$stmt = $pdo->prepare("SELECT r.*, u.first_name, u.last_name FROM reviews r JOIN users u ON r.customer_id = u.id WHERE r.apartment_id = ? ORDER BY r.created_at DESC LIMIT 5");
$stmt->execute([$apartment_id]);
$reviews = $stmt->fetchAll();

$amenities = explode(',', $apartment['amenities']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $apartment['title'] ?> - ApartmentHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-building"></i> ApartmentHub
        </a>
        <div class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="navbar-text me-3">
                    <i class="fas fa-user-circle"></i> Welcome, <?= $_SESSION['username'] ?>
                </span>
                <a class="nav-link" href="customer/reservations.php">
                    <i class="fas fa-calendar-check"></i> My Reservations
                </a>
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
</nav>

<div class="container my-5">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success glass text-white border-0"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger glass text-white border-0"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card glass border-0 mb-4">
                <div class="card-img-top position-relative" style="height: 400px; background: var(--gradient);">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle"></i> Available
                        </span>
                    </div>
                    <div class="position-absolute bottom-0 start-0 m-4">
                        <h1 class="text-white fw-bold mb-2"><?= $apartment['title'] ?></h1>
                        <p class="text-white-50 mb-0">
                            <i class="fas fa-map-marker-alt"></i> <?= $apartment['address'] ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="card glass border-0 mb-4">
                <div class="card-body">
                    <h3 class="text-white mb-4">About this place</h3>
                    <p class="text-white-50 fs-5"><?= $apartment['description'] ?></p>
                    
                    <div class="row g-4 mt-3">
                        <div class="col-md-3 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-bed fa-2x text-primary mb-2"></i>
                                <h6 class="text-white"><?= $apartment['bedrooms'] ?> Bedrooms</h6>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-bath fa-2x text-primary mb-2"></i>
                                <h6 class="text-white"><?= $apartment['bathrooms'] ?> Bathrooms</h6>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-expand-arrows-alt fa-2x text-primary mb-2"></i>
                                <h6 class="text-white"><?= $apartment['area_sqm'] ?> sqm</h6>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h6 class="text-white"><?= $apartment['max_guests'] ?> Guests</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card glass border-0 mb-4">
                <div class="card-body">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-star"></i> Amenities
                    </h5>
                    <div class="row g-3">
                        <?php foreach ($amenities as $amenity): ?>
                            <div class="col-md-6">
                                <div class="amenity-item p-3 rounded-3" style="background: rgba(255,255,255,0.1);">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="text-white"><?= trim($amenity) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="card glass border-0">
                <div class="card-header bg-transparent border-0">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-star"></i> Reviews
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer'): ?>
                        <div class="mb-4 p-4 rounded-3" style="background: rgba(255,255,255,0.1);">
                            <h6 class="text-white mb-3">Write a Review</h6>
                            <form action="actions/submit_review.php" method="POST">
                                <input type="hidden" name="apartment_id" value="<?= $apartment['id'] ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label text-white">Rating</label>
                                    <div class="rating">
                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required>
                                            <label for="star<?= $i ?>">★</label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..." required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-paper-plane"></i> Submit Review
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($reviews): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="mb-3 p-3 rounded-3" style="background: rgba(255,255,255,0.1);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-white"><?= $review['first_name'] . ' ' . $review['last_name'] ?></strong>
                                    <div>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="text-<?= $i <= $review['rating'] ? 'warning' : 'muted' ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-white-50 mb-1"><?= $review['comment'] ?></p>
                                <small class="text-white-50"><?= date('M d, Y', strtotime($review['created_at'])) ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-white-50">No reviews yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card glass border-0 sticky-top" style="top: 2rem;">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3 class="text-white fw-bold"><?= formatCurrency($apartment['price_per_night']) ?></h3>
                        <small class="text-white-50">per night</small>
                    </div>
                    
                    <form action="actions/make_reservation.php" method="POST" class="reservation-form">
                        <input type="hidden" name="apartment_id" value="<?= $apartment['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label text-white">
                                <i class="fas fa-calendar-alt"></i> Check-in
                            </label>
                            <input type="date" name="check_in" class="form-control" value="<?= $check_in ?>" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-white">
                                <i class="fas fa-calendar-alt"></i> Check-out
                            </label>
                            <input type="date" name="check_out" class="form-control" value="<?= $check_out ?>" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-white">
                                <i class="fas fa-users"></i> Guests
                            </label>
                            <select name="guests" class="form-select" required>
                                <?php for ($i = 1; $i <= $apartment['max_guests']; $i++): ?>
                                    <option value="<?= $i ?>" <?= $guests == $i ? 'selected' : '' ?>>
                                        <?= $i ?> Guest<?= $i > 1 ? 's' : '' ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-white">
                                <i class="fas fa-credit-card"></i> Payment Method
                            </label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select Payment Method</option>
                                <option value="credit_card">💳 Credit Card</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                                <option value="gcash">📱 GCash</option>
                                <option value="paymaya">💰 PayMaya</option>
                                <option value="cash">💵 Cash</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-white">
                                <i class="fas fa-comment"></i> Special Requests
                            </label>
                            <textarea name="special_requests" class="form-control" rows="3" placeholder="Any special requests or notes"></textarea>
                        </div>
                        
                        <?php if ($total_nights > 0): ?>
                            <div class="pricing-breakdown mb-4 p-3 rounded-3" style="background: rgba(255,255,255,0.1);">
                                <div class="d-flex justify-content-between text-white-50 mb-2">
                                    <span><?= formatCurrency($apartment['price_per_night']) ?> × <?= $total_nights ?> nights</span>
                                    <span><?= formatCurrency($total_amount) ?></span>
                                </div>
                                <hr class="border-white-50">
                                <div class="d-flex justify-content-between text-white fw-bold fs-5">
                                    <span>Total</span>
                                    <span><?= formatCurrency($total_amount) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                <i class="fas fa-calendar-check"></i> Reserve Now
                            </button>
                            <small class="text-white-50 d-block text-center mt-2">
                                <i class="fas fa-shield-alt"></i> Secure booking with instant confirmation
                            </small>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary w-100 py-3 fw-bold">
                                <i class="fas fa-sign-in-alt"></i> Login to Reserve
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    font-size: 1.5rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffc107;
}
</style>

</body>
</html>
