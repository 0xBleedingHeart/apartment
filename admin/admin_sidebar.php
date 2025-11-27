<div class="col-md-3">
    <div class="list-group">
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="dashboard.php" class="list-group-item">Dashboard</a>
            <a href="rooms.php" class="list-group-item">Rooms</a>
            <a href="tenants.php" class="list-group-item">Tenants</a>
            <a href="contracts.php" class="list-group-item">Contracts</a>
            <a href="payments.php" class="list-group-item">Payments</a>
            <a href="maintenance.php" class="list-group-item">Maintenance</a>
        <?php else: ?>
            <a href="dashboard.php" class="list-group-item">Dashboard</a>
            <a href="my_room.php" class="list-group-item">My Room</a>
            <a href="payment_history.php" class="list-group-item">Payment History</a>
            <a href="make_payment.php" class="list-group-item">Make Payment</a>
            <a href="report_issue.php" class="list-group-item">Report Issue</a>
        <?php endif; ?>
    </div>
</div>
