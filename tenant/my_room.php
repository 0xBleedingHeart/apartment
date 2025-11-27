<?php
require_once '../config/init.php';
checkTenant();

$tenant_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT c.*, r.* FROM contracts c JOIN rooms r ON c.room_id = r.id WHERE c.tenant_id = ? AND c.status = 'active'");
$stmt->execute([$tenant_id]);
$contract = $stmt->fetch();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>My Room</h2>
            
            <?php if ($contract): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <?php if ($contract['image']): ?>
                                    <img src="../assets/uploads/<?= $contract['image'] ?>" class="img-fluid" alt="Room Image">
                                <?php else: ?>
                                    <div class="bg-light p-5 text-center">No Image Available</div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <h4>Room <?= $contract['room_number'] ?></h4>
                                <p><strong>Type:</strong> <?= ucfirst($contract['room_type']) ?></p>
                                <p><strong>Monthly Rent:</strong> <?= formatCurrency($contract['monthly_rent']) ?></p>
                                <p><strong>Contract Start:</strong> <?= date('M d, Y', strtotime($contract['start_date'])) ?></p>
                                <p><strong>Status:</strong> <span class="badge bg-success"><?= ucfirst($contract['status']) ?></span></p>
                                
                                <?php if ($contract['description']): ?>
                                    <div class="mt-3">
                                        <h6>Room Description:</h6>
                                        <p><?= $contract['description'] ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">Contract Terms</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Monthly Rent:</strong> <?= formatCurrency($contract['monthly_rent']) ?></p>
                                <p><strong>Security Deposit:</strong> <?= formatCurrency($contract['deposit']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Contract Start:</strong> <?= date('M d, Y', strtotime($contract['start_date'])) ?></p>
                                <p><strong>Status:</strong> <?= ucfirst($contract['status']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    You don't have an active room contract. Please contact the administrator.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
