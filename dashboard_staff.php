<?php
session_start();
include "includes/db_connect.php";

// 1. Sekatan Peranan: Hanya Staff dibenarkan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .order-table { width: 100%; border-collapse: collapse; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .order-table th { background-color: #28a745; color: white; padding: 12px; text-align: left; }
        .order-table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px; color: white; }
        .btn-edit { background: #007bff; }
    </style>
</head>
<body>
    <h1>Staff Dashboard: Inventory & Orders</h1>
    <p>Logged in as: <b><?php echo $_SESSION['name']; ?></b> (Staff Access)</p>

    <h3>Manage Order Status</h3>
    <table class="order-table">
        <thead>
            <tr>
                <th>Order ID</th><th>User ID</th><th>Controller ID</th><th>Quantity</th><th>Status</th><th>Action</th>
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
                            <a href='edit_order.php?id={$row['order_id']}' class='btn btn-edit'>Update Status</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

    <h3>Inventory Stock Levels</h3>
    <table class="order-table">
        <thead>
            <tr>
                <th>Controller Model</th><th>Price</th><th>Stock Quantity</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res_stock = mysqli_query($conn, "SELECT * FROM controllers");
            while ($row = mysqli_fetch_assoc($res_stock)) {
                echo "<tr>
                        <td>{$row['model_name']}</td>
                        <td>RM {$row['price']}</td>
                        <td>{$row['stock_quantity']}</td>
                        <td>
                            <a href='edit_stock.php?id={$row['controller_id']}' class='btn btn-edit'>Edit Stock</a>
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