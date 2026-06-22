<?php
include "includes/db_connect.php";

$id = $_GET['id'];
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, "SELECT * FROM preorders WHERE order_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>

<h2>Edit Order Penuh (Full Override)</h2>
<form action="php/manage-orders.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['order_id']; ?>">
    
    <label>User ID:</label><br>
    <input type="text" name="user_id" value="<?php echo $row['user_id']; ?>" required><br>

    <label>Controller ID:</label><br>
    <input type="text" name="controller_id" value="<?php echo $row['controller_id']; ?>" required><br>

    <label>Quantity:</label><br>
    <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" required><br>

    <label>Status:</label><br>
    <select name="status">
        <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
        <option value="Approved" <?php if($row['status']=='Approved') echo 'selected'; ?>>Approved</option>
        <option value="Shipped" <?php if($row['status']=='Shipped') echo 'selected'; ?>>Shipped</option>
    </select><br><br>
    
    <button type="submit" name="update_full_order">Save All Changes</button>
</form>