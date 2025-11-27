<?php
require_once '../config/init.php';
checkAdmin();

$stmt = $pdo->query("SELECT c.*, u.first_name, u.last_name, r.room_number FROM contracts c JOIN users u ON c.tenant_id = u.id JOIN rooms r ON c.room_id = r.id ORDER BY c.created_at DESC");
$contracts = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include './admin_sidebar.php'; ?>
        
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Contracts Management</h2>
                <a href="add_contract.php" class="btn btn-primary">Add New Contract</a>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tenant</th>
                                <th>Room</th>
                                <th>Start Date</th>
                                <th>Monthly Rent</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contracts as $contract): ?>
                            <tr>
                                <td><?= $contract['first_name'] . ' ' . $contract['last_name'] ?></td>
                                <td>Room <?= $contract['room_number'] ?></td>
                                <td><?= date('M d, Y', strtotime($contract['start_date'])) ?></td>
                                <td><?= formatCurrency($contract['monthly_rent']) ?></td>
                                <td><span class="badge bg-<?= $contract['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($contract['status']) ?></span></td>
                                <td>
                                    <?php if ($contract['status'] === 'active'): ?>
                                        <a href="../actions/end_contract.php?id=<?= $contract['id'] ?>" class="btn btn-sm btn-warning" onclick="return confirm('End this contract?')">End Contract</a>
                                    <?php endif; ?>
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
