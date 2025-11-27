<?php
require_once '../config/init.php';
checkCustomer();

$reservation_id = intval($_GET['reservation_id']);
$customer_id = $_SESSION['user_id'];

// Get reservation details
$stmt = $pdo->prepare("SELECT r.*, a.title FROM reservations r JOIN apartments a ON r.apartment_id = a.id WHERE r.id = ? AND r.customer_id = ? AND r.status = 'completed'");
$stmt->execute([$reservation_id, $customer_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    header('Location: reservations.php');
    exit();
}

// Check if review already exists
$stmt = $pdo->prepare("SELECT id FROM reviews WHERE reservation_id = ?");
$stmt->execute([$reservation_id]);
$existing_review = $stmt->fetch();

if ($existing_review) {
    header('Location: reservations.php?error=Review already submitted');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Review - ApartmentHub</title>
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
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <h1 class="hero-title">Write Review</h1>
                <p class="text-white-50">Share your experience with other guests</p>
            </div>
            
            <div class="card glass border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="text-white fw-bold mb-2"><?= $reservation['title'] ?></h5>
                    <p class="text-white-50 mb-0">
                        <i class="fas fa-calendar-alt"></i> 
                        Stay: <?= date('M d, Y', strtotime($reservation['check_in'])) ?> - <?= date('M d, Y', strtotime($reservation['check_out'])) ?>
                    </p>
                </div>
            </div>
            
            <div class="card glass border-0">
                <div class="card-body p-5">
                    <form action="../actions/submit_review.php" method="POST">
                        <input type="hidden" name="reservation_id" value="<?= $reservation_id ?>">
                        <input type="hidden" name="apartment_id" value="<?= $reservation['apartment_id'] ?>">
                        
                        <div class="mb-4">
                            <label class="form-label text-white fs-5 mb-3">
                                <i class="fas fa-star"></i> Rating
                            </label>
                            <div class="rating justify-content-center">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required>
                                    <label for="star<?= $i ?>">★</label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-white fs-5">
                                <i class="fas fa-comment"></i> Your Review
                            </label>
                            <textarea name="comment" class="form-control" rows="6" placeholder="Share your experience with this apartment..." required></textarea>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary flex-fill py-3 fw-bold">
                                <i class="fas fa-paper-plane"></i> Submit Review
                            </button>
                            <a href="reservations.php" class="btn btn-outline-light flex-fill py-3 fw-bold">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
