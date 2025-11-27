<?php
require_once '../config/init.php';
checkAdmin();

$apartment_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM apartments WHERE id = ?");
$stmt->execute([$apartment_id]);
$apartment = $stmt->fetch();

if (!$apartment) {
    header('Location: apartments.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Apartment - ApartmentHub Admin</title>
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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <h1 class="hero-title">Edit Apartment</h1>
                <p class="text-white-50">Update apartment details</p>
            </div>
            
            <div class="card glass border-0">
                <div class="card-body p-5">
                    <form action="../actions/update_apartment.php" method="POST">
                        <input type="hidden" name="apartment_id" value="<?= $apartment['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-white">
                                    <i class="fas fa-home"></i> Title
                                </label>
                                <input type="text" name="title" class="form-control" value="<?= $apartment['title'] ?>" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-white">
                                    <i class="fas fa-money-bill-wave"></i> Price per Night
                                </label>
                                <input type="number" name="price_per_night" class="form-control" step="0.01" value="<?= $apartment['price_per_night'] ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-white">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </label>
                            <textarea name="address" class="form-control" rows="2" required><?= $apartment['address'] ?></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-white">
                                <i class="fas fa-align-left"></i> Description
                            </label>
                            <textarea name="description" class="form-control" rows="4" required><?= $apartment['description'] ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <label class="form-label text-white">
                                    <i class="fas fa-bed"></i> Bedrooms
                                </label>
                                <input type="number" name="bedrooms" class="form-control" min="1" value="<?= $apartment['bedrooms'] ?>" required>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label text-white">
                                    <i class="fas fa-bath"></i> Bathrooms
                                </label>
                                <input type="number" name="bathrooms" class="form-control" min="1" value="<?= $apartment['bathrooms'] ?>" required>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label text-white">
                                    <i class="fas fa-expand-arrows-alt"></i> Area (sqm)
                                </label>
                                <input type="number" name="area_sqm" class="form-control" min="1" value="<?= $apartment['area_sqm'] ?>">
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label text-white">
                                    <i class="fas fa-users"></i> Max Guests
                                </label>
                                <input type="number" name="max_guests" class="form-control" min="1" value="<?= $apartment['max_guests'] ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-white">
                                <i class="fas fa-star"></i> Amenities (comma-separated)
                            </label>
                            <input type="text" name="amenities" class="form-control" value="<?= $apartment['amenities'] ?>" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-white">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <select name="status" class="form-select" required>
                                <option value="available" <?= $apartment['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                                <option value="unavailable" <?= $apartment['status'] === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                            </select>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary flex-fill py-3 fw-bold">
                                <i class="fas fa-save"></i> Update Apartment
                            </button>
                            <a href="apartments.php" class="btn btn-outline-light flex-fill py-3 fw-bold">
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
