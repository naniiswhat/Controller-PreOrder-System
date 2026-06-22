<?php
// Pastikan hanya admin boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}
?>

<style>
    .container { max-width: 1000px; margin: 20px auto; font-family: sans-serif; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; }
    th { background: #333; color: white; padding: 12px 15px; text-align: left; }
    td { padding: 12px 15px; border-bottom: 1px solid #eee; }
    tr:hover { background-color: #f9f9f9; }
</style>

<h3>Manage Pre-orders (CRUD Manager)</h3>
<a href="add_order.php" style="background:#28a745; color:white; padding:5px 10px; text-decoration:none;">+ Add New Order</a>

<table>
    <tr>
        <th>ID</th><th>User</th><th>Controller</th><th>Qty</th><th>Status</th><th>Actions</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM preorders");
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['user_id']}</td>
                <td>{$row['controller_id']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='edit_order.php?id={$row['order_id']}'>Edit</a> | 
                    <a href='home.php?page=orders&delete={$row['order_id']}' onclick='return confirm(\"Confirm?\")' style='color:red;'>Delete</a>
                </td>
              </tr>";
    }
    ?>
</table>