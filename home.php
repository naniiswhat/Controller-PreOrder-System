<?php
session_start();
include "includes/db_connect.php";

// Pastikan user dah login
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Utama</title>
    <style>
        .container { max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; font-family: sans-serif; text-align: center; }
        .admin-task-room { background-color: #e0f7fa; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: left; }
        .btn-logout { display: block; background: #333; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        ul { list-style: none; padding: 0; }
        li { margin: 10px 0; }
        li a { text-decoration: none; color: #006064; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">admin logo</div>
    <h2>Welcome <?php echo ucfirst($_SESSION['role']); ?>: <?php echo $_SESSION['name']; ?></h2>
    
    <p style="color: <?php echo ($_SESSION['role'] == 'admin') ? 'red' : 'green'; ?>; font-weight: bold;">
        System Clearance: <?php echo ($_SESSION['role'] == 'admin') ? 'Full Override Privilege' : 'Standard Operational Access'; ?>
    </p>

    <div class="admin-task-room">
        <h3>Admin Task Room:</h3>
        <ul>
            <li><a href="edit_products.php">📦 Add New Controller Variants</a></li>
            <li><a href="php/manage-orders.php">⚙️ View Analytics</a></li>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="manage_users.php">👤 Manage User Accounts</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <a href="logout.php" class="btn-logout">Sign Out</a>
</div>

</body>
</html>