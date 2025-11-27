<?php
require_once '../config/init.php';
checkAdmin();

$stmt = $pdo->query("SELECT m.*, u.first_name, u.last_name, r.room_number FROM maintenance m JOIN users u ON m.tenant_id = u.id LEFT JOIN rooms r ON m.room_id = r.id ORDER BY m.created_at DESC");
$issues = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <?php include './admin_sidebar.php'; ?>
        
        <div class="col-md-9">
            <h2>Maintenance Issues</h2>
            
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tenant</th>
                                <th>Room</th>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($issues as $issue): ?>
                            <tr>
                                <td><?= $issue['first_name'] . ' ' . $issue['last_name'] ?></td>
                                <td><?= $issue['room_number'] ?: 'N/A' ?></td>
                                <td><?= $issue['title'] ?></td>
                                <td><span class="badge bg-<?= $issue['priority'] === 'urgent' ? 'danger' : ($issue['priority'] === 'high' ? 'warning' : 'info') ?>"><?= ucfirst($issue['priority']) ?></span></td>
                                <td><span class="badge bg-<?= $issue['status'] === 'completed' ? 'success' : ($issue['status'] === 'in_progress' ? 'warning' : 'secondary') ?>"><?= ucfirst(str_replace('_', ' ', $issue['status'])) ?></span></td>
                                <td><?= date('M d, Y', strtotime($issue['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#issueModal<?= $issue['id'] ?>">View</button>
                                    <?php if ($issue['status'] !== 'completed'): ?>
                                        <a href="../actions/update_issue.php?id=<?= $issue['id'] ?>&status=completed" class="btn btn-sm btn-success">Complete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <!-- Modal for issue details -->
                            <div class="modal fade" id="issueModal<?= $issue['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?= $issue['title'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Description:</strong> <?= $issue['description'] ?></p>
                                            <p><strong>Priority:</strong> <?= ucfirst($issue['priority']) ?></p>
                                            <p><strong>Status:</strong> <?= ucfirst(str_replace('_', ' ', $issue['status'])) ?></p>
                                            <p><strong>Reported:</strong> <?= date('M d, Y H:i', strtotime($issue['created_at'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
