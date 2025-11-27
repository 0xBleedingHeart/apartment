<?php
require_once '../config/init.php';
checkTenant();

$tenant_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT r.* FROM rooms r JOIN contracts c ON r.id = c.room_id WHERE c.tenant_id = ? AND c.status = 'active'");
$stmt->execute([$tenant_id]);
$room = $stmt->fetch();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Report Maintenance Issue</h2>
            
            <div class="card">
                <div class="card-body">
                    <form action="../actions/submit_issue.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Issue Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required placeholder="Please describe the issue in detail"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-control" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        
                        <?php if ($room): ?>
                            <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Room</label>
                                <input type="text" class="form-control" value="Room <?= $room['room_number'] ?>" readonly>
                            </div>
                        <?php endif; ?>
                        
                        <button type="submit" class="btn btn-primary">Submit Issue</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
