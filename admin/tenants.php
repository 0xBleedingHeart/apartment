<?php
require_once '../config/init.php';
checkAdmin();

$filter = $_GET['filter'] ?? 'all';
$where = $filter === 'all' ? "" : "AND status = '$filter'";

$stmt = $pdo->query("SELECT * FROM users WHERE role = 'tenant' $where ORDER BY created_at DESC");
$tenants = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include './admin_sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Tenants Management</h2>
            
            <div class="mb-3">
                <a href="?filter=all" class="btn btn-outline-primary <?= $filter === 'all' ? 'active' : '' ?>">All</a>
                <a href="?filter=active" class="btn btn-outline-success <?= $filter === 'active' ? 'active' : '' ?>">Active</a>
                <a href="?filter=pending" class="btn btn-outline-warning <?= $filter === 'pending' ? 'active' : '' ?>">Pending</a>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tenants as $tenant): ?>
                            <tr>
                                <td><?= $tenant['first_name'] . ' ' . $tenant['last_name'] ?></td>
                                <td><?= $tenant['email'] ?></td>
                                <td><?= $tenant['phone'] ?></td>
                                <td><span class="badge bg-<?= $tenant['status'] === 'active' ? 'success' : 'warning' ?>"><?= ucfirst($tenant['status']) ?></span></td>
                                <td><?= date('M d, Y', strtotime($tenant['created_at'])) ?></td>
                                <td>
                                    <a href="view_tenant.php?id=<?= $tenant['id'] ?>" class="btn btn-sm btn-info">View</a>
                                    <?php if ($tenant['status'] === 'pending'): ?>
                                        <a href="../actions/update_tenant_status.php?id=<?= $tenant['id'] ?>&status=active" class="btn btn-sm btn-success">Approve</a>
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
