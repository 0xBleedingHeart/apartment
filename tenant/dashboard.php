<?php
require_once '../config/init.php';
checkTenant();

$tenant_id = $_SESSION['user_id'];

// Get tenant's current contract and room
$stmt = $pdo->prepare("SELECT c.*, r.room_number, r.room_type, r.price FROM contracts c JOIN rooms r ON c.room_id = r.id WHERE c.tenant_id = ? AND c.status = 'active'");
$stmt->execute([$tenant_id]);
$contract = $stmt->fetch();

// Get payment balance
$stmt = $pdo->prepare("SELECT SUM(amount) as total_paid FROM payments WHERE tenant_id = ? AND status = 'paid'");
$stmt->execute([$tenant_id]);
$total_paid = $stmt->fetchColumn() ?: 0;

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Welcome, <?= $_SESSION['username'] ?>!</h2>
            
            <?php if ($contract): ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">Current Room</div>
                            <div class="card-body">
                                <h5>Room <?= $contract['room_number'] ?></h5>
                                <p>Type: <?= ucfirst($contract['room_type']) ?></p>
                                <p>Monthly Rent: <?= formatCurrency($contract['price']) ?></p>
                                <p>Contract Start: <?= date('M d, Y', strtotime($contract['start_date'])) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">Payment Summary</div>
                            <div class="card-body">
                                <h5>Total Paid: <?= formatCurrency($total_paid) ?></h5>
                                <a href="make_payment.php" class="btn btn-primary">Make Payment</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    You don't have an active room contract yet. Please contact the administrator.
                </div>
            <?php endif; ?>
            
            <div class="card mt-4">
                <div class="card-header">Recent Activities</div>
                <div class="card-body">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM payments WHERE tenant_id = ? ORDER BY created_at DESC LIMIT 5");
                    $stmt->execute([$tenant_id]);
                    $payments = $stmt->fetchAll();
                    
                    if ($payments): ?>
                        <h6>Recent Payments</h6>
                        <?php foreach ($payments as $payment): ?>
                            <div class="d-flex justify-content-between">
                                <span><?= date('M d, Y', strtotime($payment['created_at'])) ?></span>
                                <span class="status-<?= $payment['status'] ?>"><?= formatCurrency($payment['amount']) ?> (<?= ucfirst($payment['status']) ?>)</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No payment history yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
