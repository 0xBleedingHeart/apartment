<?php
require_once '../config/init.php';
checkAdmin();

$stmt = $pdo->query("SELECT p.*, u.first_name, u.last_name FROM payments p JOIN users u ON p.tenant_id = u.id ORDER BY p.payment_date DESC");
$payments = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include './admin_sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Payments Management</h2>
            
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tenant</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Receipt</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?= $payment['first_name'] . ' ' . $payment['last_name'] ?></td>
                                <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                <td><?= formatCurrency($payment['amount']) ?></td>
                                <td><?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                                <td><span class="badge bg-<?= $payment['status'] === 'paid' ? 'success' : ($payment['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= ucfirst($payment['status']) ?></span></td>
                                <td>
                                    <?php if ($payment['receipt']): ?>
                                        <a href="../assets/uploads/receipts/<?= $payment['receipt'] ?>" target="_blank" class="btn btn-sm btn-info">View</a>
                                    <?php else: ?>
                                        No receipt
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($payment['status'] === 'pending'): ?>
                                        <a href="../actions/verify_payment.php?id=<?= $payment['id'] ?>&status=paid" class="btn btn-sm btn-success">Approve</a>
                                        <a href="../actions/verify_payment.php?id=<?= $payment['id'] ?>&status=rejected" class="btn btn-sm btn-danger">Reject</a>
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
