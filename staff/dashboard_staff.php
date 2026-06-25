<?php
// Pastikan sesi aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Semak peranan (hanya staf)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    die("Access Denied.");
}

include_once __DIR__ . "/../includes/db_connect.php";
?>

<h3>Staff Dashboard: Pre-orders & Inventory</h3>

<h4>Manage Pre-orders</h4>
<table border="1" width="100%" style="border-collapse:collapse; margin-bottom:40px;">
    <tr style="background:#333; color:white;">
        <th>Order ID</th><th>User</th><th>Product</th><th>Status</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT p.*, u.username, c.model_name 
                                FROM preorders p 
                                JOIN users u ON p.user_id = u.user_id 
                                JOIN controllers c ON p.controller_id = c.controller_id");
    
    while($row = mysqli_fetch_assoc($res)){
        echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['model_name']}</td>
                <td>
                    <form action='staff/update_order.php' method='POST'>
                        <input type='hidden' name='order_id' value='{$row['order_id']}'>
                        <select name='status' onchange='this.form.submit()'>
                            <option value='pending' " . ($row['status'] == 'pending' ? 'selected' : '') . ">Pending</option>
                            <option value='processing' " . ($row['status'] == 'processing' ? 'selected' : '') . ">Processing</option>
                            <option value='shipped' " . ($row['status'] == 'shipped' ? 'selected' : '') . ">Shipped</option>
                        </select>
                    </form>
                </td>
              </tr>";
    }
    ?>
</table>

<h4>Manage Inventory</h4>
<table border="1" width="100%" style="border-collapse:collapse;">
    <tr style="background:#555; color:white;">
        <th>Model Name</th><th>Current Stock</th><th>Action</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM controllers");
    while($row = mysqli_fetch_assoc($res)){
        echo "<tr>
                <td>{$row['model_name']}</td>
                <td>{$row['stock_quantity']}</td>
                <td>
                    <a href='../edit_product.php?id={$row['controller_id']}' aria-label='Edit product' title='Edit' style='display:inline-flex; width:34px; height:34px; align-items:center; justify-content:center; border:1px solid #ddd; border-radius:8px; vertical-align:middle;'>
                        <img src='../frontend/assets/icons/square-pen.svg' alt='' style='width:17px; height:17px; display:block;'>
                    </a>
                </td>
              </tr>";
    }
    ?>
</table>
