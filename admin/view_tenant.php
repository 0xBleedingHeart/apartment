<?php
require_once '../config/init.php';
checkAdmin();

$tenant_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'tenant'");
$stmt->execute([$tenant_id]);
$tenant = $stmt->fetch();

if (!$tenant) {
    header('Location: tenants.php');
    exit();
}

// Get tenant's contract
$stmt = $pdo->prepare("SELECT c.*, r.room_number FROM contracts c LEFT JOIN rooms r ON c.room_id = r.id WHERE c.tenant_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$tenant_id]);
$contracts = $stmt->fetchAll();

// Get tenant's payments
$stmt = $pdo->prepare("SELECT * FROM payments WHERE tenant_id = ? ORDER BY payment_date DESC");
$stmt->execute([$tenant_id]);
$payments = $stmt->fetchAll();

// Get tenant's maintenance issues
$stmt = $pdo->prepare("SELECT m.*, r.room_number FROM maintenance m LEFT JOIN rooms r ON m.room_id = r.id WHERE m.tenant_id = ? ORDER BY m.created_at DESC");
$stmt->execute([$tenant_id]);
$issues = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Tenant Details</h2>
                <a href="tenants.php" class="btn btn-secondary">Back to Tenants</a>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">Personal Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <?= $tenant['first_name'] . ' ' . $tenant['last_name'] ?></p>
                            <p><strong>Username:</strong> <?= $tenant['username'] ?></p>
                            <p><strong>Email:</strong> <?= $tenant['email'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> <?= $tenant['phone'] ?></p>
                            <p><strong>Status:</strong> <span class="badge bg-<?= $tenant['status'] === 'active' ? 'success' : 'warning' ?>"><?= ucfirst($tenant['status']) ?></span></p>
                            <p><strong>Joined:</strong> <?= date('M d, Y', strtotime($tenant['created_at'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">Contract History</div>
                <div class="card-body">
                    <?php if ($contracts): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Monthly Rent</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contracts as $contract): ?>
                                <tr>
                                    <td>Room <?= $contract['room_number'] ?></td>
                                    <td><?= date('M d, Y', strtotime($contract['start_date'])) ?></td>
                                    <td><?= $contract['end_date'] ? date('M d, Y', strtotime($contract['end_date'])) : 'Active' ?></td>
                                    <td><?= formatCurrency($contract['monthly_rent']) ?></td>
                                    <td><span class="badge bg-<?= $contract['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($contract['status']) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No contracts found.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">Payment History</div>
                <div class="card-body">
                    <?php if ($payments): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                    <td><?= formatCurrency($payment['amount']) ?></td>
                                    <td><?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                                    <td><span class="badge bg-<?= $payment['status'] === 'paid' ? 'success' : ($payment['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= ucfirst($payment['status']) ?></span></td>
                                    <td>
                                        <?php if ($payment['receipt']): ?>
                                            <a href="../assets/uploads/receipts/<?= $payment['receipt'] ?>" target="_blank">View</a>
                                        <?php else: ?>
                                            No receipt
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No payment history found.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">Maintenance Issues</div>
                <div class="card-body">
                    <?php if ($issues): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Title</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($issues as $issue): ?>
                                <tr>
                                    <td>Room <?= $issue['room_number'] ?: 'N/A' ?></td>
                                    <td><?= $issue['title'] ?></td>
                                    <td><span class="badge bg-<?= $issue['priority'] === 'urgent' ? 'danger' : ($issue['priority'] === 'high' ? 'warning' : 'info') ?>"><?= ucfirst($issue['priority']) ?></span></td>
                                    <td><span class="badge bg-<?= $issue['status'] === 'completed' ? 'success' : ($issue['status'] === 'in_progress' ? 'warning' : 'secondary') ?>"><?= ucfirst(str_replace('_', ' ', $issue['status'])) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($issue['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No maintenance issues reported.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
