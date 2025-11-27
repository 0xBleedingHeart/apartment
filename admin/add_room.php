<?php
require_once '../config/init.php';
checkAdmin();
include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Add New Room</h2>
            
            <div class="card">
                <div class="card-body">
                    <form action="../actions/save_room.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Number</label>
                                <input type="text" name="room_number" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Type</label>
                                <select name="room_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="single">Single</option>
                                    <option value="double">Double</option>
                                    <option value="shared">Shared</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Monthly Price</label>
                                <input type="number" name="price" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="available">Available</option>
                                    <option value="occupied">Occupied</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Room Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                            <img id="imagePreview" src="#" alt="Preview" style="max-width: 200px; margin-top: 10px; display: none;">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save Room</button>
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
