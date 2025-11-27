<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding House Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Boarding House</a>
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="navbar-nav ms-auto">
            <span class="navbar-text me-3">Welcome, <?= $_SESSION['username'] ?></span>
            <a class="nav-link" href="../logout.php">Logout</a>
        </div>
        <?php endif; ?>
    </div>
</nav>
