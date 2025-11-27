<?php
require_once '../config/init.php';
checkAdmin();

$stmt = $pdo->query("SELECT * FROM rooms ORDER BY room_number");
$rooms = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include './admin_sidebar.php'; ?>
        
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Rooms Management</h2>
                <a href="add_room.php" class="btn btn-primary">Add New Room</a>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Room #</th>
                                <th>Image</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                            <tr>
                                <td><?= $room['room_number'] ?></td>
                                <td>
                                    <?php if ($room['image']): ?>
                                        <img src="../assets/uploads/<?= $room['image'] ?>" class="room-image" alt="Room">
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>
                                <td><?= $room['room_type'] ?></td>
                                <td><?= formatCurrency($room['price']) ?></td>
                                <td><span class="status-<?= $room['status'] ?>"><?= ucfirst($room['status']) ?></span></td>
                                <td>
                                    <a href="edit_room.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="../actions/delete_room.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a>
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

<?php include '../includes/footer.php'; ?>
