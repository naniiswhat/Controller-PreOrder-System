<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

// Kira statistik ringkas
$count_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_sales = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(price) as total FROM controllers"))['total']; // Contoh ambil dari table controllers
?>

<h3>System Analytics</h3>

<div style="display: flex; gap: 20px; margin-bottom: 30px;">
    <div style="background: #3498db; color: white; padding: 20px; border-radius: 8px; width: 200px;">
        <h4>Total Users</h4>
        <p style="font-size: 24px; font-weight: bold;"><?php echo $count_users; ?></p>
    </div>
    
    <div style="background: #27ae60; color: white; padding: 20px; border-radius: 8px; width: 200px;">
        <h4>Total Inventory Value</h4>
        <p style="font-size: 24px; font-weight: bold;">RM <?php echo number_format($total_sales, 2); ?></p>
    </div>
</div>

<h4>Recent Activity</h4>
<table style="width:100%; border-collapse:collapse;">
    <tr style="background:#eee;">
        <th>ID</th><th>User</th><th>Role</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM users ORDER BY user_id DESC LIMIT 5");
    while($row = mysqli_fetch_assoc($res)){
        echo "<tr><td>{$row['user_id']}</td><td>{$row['username']}</td><td>{$row['role']}</td></tr>";
    }
    ?>
</table>