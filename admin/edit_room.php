<?php
require_once '../config/init.php';
checkAdmin();

$room_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

if (!$room) {
    header('Location: rooms.php');
    exit();
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Edit Room</h2>
            
            <div class="card">
                <div class="card-body">
                    <form action="../actions/update_room.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Number</label>
                                <input type="text" name="room_number" class="form-control" value="<?= $room['room_number'] ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Type</label>
                                <select name="room_type" class="form-control" required>
                                    <option value="single" <?= $room['room_type'] === 'single' ? 'selected' : '' ?>>Single</option>
                                    <option value="double" <?= $room['room_type'] === 'double' ? 'selected' : '' ?>>Double</option>
                                    <option value="shared" <?= $room['room_type'] === 'shared' ? 'selected' : '' ?>>Shared</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Monthly Price</label>
                                <input type="number" name="price" class="form-control" step="0.01" value="<?= $room['price'] ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="available" <?= $room['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                                    <option value="occupied" <?= $room['status'] === 'occupied' ? 'selected' : '' ?>>Occupied</option>
                                    <option value="maintenance" <?= $room['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= $room['description'] ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Room Image</label>
                            <?php if ($room['image']): ?>
                                <div class="mb-2">
                                    <img src="../assets/uploads/<?= $room['image'] ?>" class="room-image" alt="Current Image">
                                    <p class="text-muted">Current image</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                            <img id="imagePreview" src="#" alt="Preview" style="max-width: 200px; margin-top: 10px; display: none;">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Room</button>
                        <a href="rooms.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../includes/footer.php'; ?>
