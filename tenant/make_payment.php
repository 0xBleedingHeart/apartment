<?php
require_once '../config/init.php';
checkTenant();
include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Make Payment</h2>
            
            <div class="card">
                <div class="card-body">
                    <form action="../actions/submit_payment.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Payment Amount</label>
                            <input type="number" name="amount" class="form-control" step="0.01" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="gcash">GCash</option>
                                <option value="paymaya">PayMaya</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Receipt/Proof (Optional)</label>
                            <input type="file" name="receipt" class="form-control" accept="image/*">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this payment"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
