<?php
include "includes/db_connect.php";

// Pastikan ID wujud dalam URL
if (!isset($_GET['id'])) {
    die("Error: No Order ID provided.");
}

$id = $_GET['id'];

// Ambil data order
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, "SELECT * FROM preorders WHERE order_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

// Jika ID tidak dijumpai dalam database
if (!$row) {
    die("Error: Order not found.");
}
?>

<h2>Edit Order Status</h2>
<form action="php/manage-orders.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['order_id']; ?>">
    
    <p>Order ID: <?php echo $row['order_id']; ?></p>
    
    <label>Status:</label>
    <select name="status">
        <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
        <option value="processing" <?php if($row['status']=='processing') echo 'selected'; ?>>Processing</option>
        <option value="shipped" <?php if($row['status']=='shipped') echo 'selected'; ?>>Shipped</option>
    </select>
    
    <button type="submit" name="update_order">Update Status</button>
</form>