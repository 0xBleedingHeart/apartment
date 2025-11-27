<?php
require_once '../config/init.php';
checkAdmin();

$tenants = $pdo->query("SELECT * FROM users WHERE role = 'tenant' AND status = 'active' ORDER BY first_name")->fetchAll();
$rooms = $pdo->query("SELECT * FROM rooms WHERE status = 'available' ORDER BY room_number")->fetchAll();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Add New Contract</h2>
            
            <div class="card">
                <div class="card-body">
                    <form action="../actions/save_contract.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tenant</label>
                                <select name="tenant_id" class="form-control" required>
                                    <option value="">Select Tenant</option>
                                    <?php foreach ($tenants as $tenant): ?>
                                        <option value="<?= $tenant['id'] ?>"><?= $tenant['first_name'] . ' ' . $tenant['last_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room</label>
                                <select name="room_id" class="form-control" required onchange="updateRent(this)">
                                    <option value="">Select Room</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= $room['id'] ?>" data-price="<?= $room['price'] ?>">Room <?= $room['room_number'] ?> - <?= formatCurrency($room['price']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Monthly Rent</label>
                                <input type="number" name="monthly_rent" id="monthly_rent" class="form-control" step="0.01" required readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Security Deposit</label>
                            <input type="number" name="deposit" class="form-control" step="0.01" value="0">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create Contract</button>
                        <a href="contracts.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateRent(select) {
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    document.getElementById('monthly_rent').value = price || '';
}
</script>

<?php include '../includes/footer.php'; ?>
