<?php
include "includes/db_connect.php";

// rolebased access control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, "SELECT * FROM preorders WHERE order_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Order Full Override</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5 d-flex justify-content-center">
        <div class="card shadow p-4 w-100" style="max-width: 500px;">
            <h3 class="mb-4 text-center">Edit Order Parameters</h3>
            <form action="php/manage-orders.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['order_id']; ?>">
                
                <div class="mb-3">
                    <label class="form-label">User ID:</label>
                    <input type="text" class="form-control" name="user_id" value="<?php echo $row['user_id']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Controller ID:</label>
                    <input type="text" class="form-control" name="controller_id" value="<?php echo $row['controller_id']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity:</label>
                    <input type="number" class="form-control" name="quantity" value="<?php echo $row['quantity']; ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Status:</label>
                    <select name="status" class="form-select">
                        <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
                        <option value="processing" <?php if($row['status']=='processing') echo 'selected'; ?>>Processing</option>
                        <option value="shipped" <?php if($row['status']=='shipped') echo 'selected'; ?>>Shipped</option>
                        <option value="cancelled" <?php if($row['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                    </select>
                </div>
                
                <button type="submit" name="update_full_order" class="btn btn-success w-100 mb-2">Save All Changes</button>
                <a href="home.php" class="btn btn-outline-secondary w-100">Cancel Override</a>
            </form>
        </div>
    </div>
</body>
</html>