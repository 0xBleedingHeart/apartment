<?php
require_once '../config/init.php';
checkTenant();

$tenant_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM payments WHERE tenant_id = ? ORDER BY payment_date DESC");
$stmt->execute([$tenant_id]);
$payments = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Payment History</h2>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Receipt</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($payments): ?>
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
                                    <td><?= $payment['notes'] ?: '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No payment history found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
