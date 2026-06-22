<?php
// Mula sesi dan sambungan database
session_start();
include "includes/db_connect.php";

// 1. Sekuriti: Pastikan user sudah log masuk
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f4f4f9; padding: 20px; }
        .container { max-width: 950px; margin: auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .task-room { background: #e0f7fa; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #b2ebf2; }
        .menu-list { list-style: none; padding: 0; display: flex; gap: 20px; }
        .menu-list a { text-decoration: none; color: #006064; font-weight: bold; padding: 5px 10px; border-radius: 4px; transition: 0.3s; }
        .menu-list a:hover { background: #b2ebf2; }
        .btn-logout { background: #333; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome <?php echo ucfirst($_SESSION['role']); ?>: <?php echo $_SESSION['name']; ?></h2>
    <p style="color: <?php echo ($_SESSION['role'] == 'admin') ? 'red' : 'green'; ?>; font-weight: bold;">
        System Clearance: <?php echo ($_SESSION['role'] == 'admin') ? 'Full Override Privilege' : 'Standard Operational Access'; ?>
    </p>

    <div class="task-room">
        <h3>Task Room:</h3>
        <ul class="menu-list">
            <li><a href="home.php?page=inventory">📦 Controller Variants Management</a></li>
            <li><a href="home.php?page=analytics">⚙️ View Analytics</a></li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="home.php?page=users">👤 Manage Users</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="content-area" style="min-height: 300px; padding-top: 10px;">
        <?php
        $page = $_GET['page'] ?? 'default';
        
        // Senarai fail yang dibenarkan (White-listing untuk keselamatan)
        $allowed_pages = [
            'orders'    => 'manage_orders.php',
            'inventory' => 'manage_inventory.php',
            'users'     => 'manage_users.php',
            'analytics' => 'view_analytics.php'
        ];

        if (array_key_exists($page, $allowed_pages)) {
            $file = __DIR__ . '/' . $allowed_pages[$page];
            if (file_exists($file)) {
                include $file;
            } else {
                echo "<p style='color:red;'>Ralat: Fail <b>{$allowed_pages[$page]}</b> tidak ditemui di direktori.</p>";
            }
        } else {
            echo "<h3>Selamat Datang ke Sistem Admin</h3><p>Sila pilih menu di atas untuk memulakan tugasan.</p>";
        }
        ?>
    </div>

    <hr>
    <a href="logout.php" class="btn-logout">Sign Out</a>
</div>

</body>
</html>