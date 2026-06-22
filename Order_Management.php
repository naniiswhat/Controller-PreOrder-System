<?php
session_start();
include "includes/db_connect.php";

// Strict Role-Based Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch Dashboard Statistics
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM preorders"))['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .stats-container { background: #f4f4f4; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .order-table { width: 100%; border-collapse: collapse; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .order-table th { background-color: #333; color: white; padding: 12px; text-align: left; }
        .order-table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .order-table tr:hover { background-color: #f5f5f5; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px; }
        .btn-edit { background: #007bff; color: white; }
        .btn-delete { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <h1>Welcome Admin: <?php echo $_SESSION['name']; ?></h1>
    <p>System Clearance: <b>Full Override Privilege</b></p>

    <div class="stats-container">
        <h3>Platform Overview</h3>
        <p>Total Registered Users: <?php echo $user_count; ?></p>
        <p>Total Pre-orders: <?php echo $order_count; ?></p>
    </div>

    <h3>Order Management</h3>
    <table class="order-table">
        <thead>
            <tr>
                <th>Order ID</th><th>User ID</th><th>Controller ID</th><th>Quantity</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = mysqli_query($conn, "SELECT * FROM preorders");
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td>{$row['order_id']}</td>
                        <td>{$row['user_id']}</td>
                        <td>{$row['controller_id']}</td>
                        <td>{$row['quantity']}</td>
                        <td><strong>{$row['status']}</strong></td>
                        <td>
                            <a href='edit_order.php?id={$row['order_id']}' class='btn btn-edit'>Edit</a>
                            <a href='php/manage-orders.php?delete={$row['order_id']}' class='btn btn-delete' onclick='return confirm(\"Confirm delete?\")'>Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a href="logout.php">Sign Out</a>
</body>
</html>