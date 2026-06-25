<?php
session_start();
include "includes/db_connect.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: frontend/login.php");
    exit();
}
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header("Location: frontend/dashboard_admin.php"); exit(); }

$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, "SELECT * FROM preorders WHERE order_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) { header("Location: frontend/dashboard_admin.php"); exit(); }
function h($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Order | Controller Pre Order System</title>
    <link rel="stylesheet" href="frontend/styles.css">
</head>
<body data-page="admin-edit">
    <nav class="top-nav" aria-label="Backend navigation">
        <a class="brand" href="frontend/dashboard_admin.php">
            <img src="frontend/assets/logo.svg" alt="Logo">
        </a>
        <a href="logout.php">Logout</a>
    </nav>
    <main>
        <section class="page backend-edit-page">
            <header class="admin-header">
                <div>
                    <h1>Edit order</h1>
                    <p class="small-link">Order #PO-<?php echo h($row['order_id']); ?> parameter override.</p>
                </div>
                <a class="btn secondary" href="frontend/dashboard_admin.php">Back to dashboard</a>
            </header>
            <form class="backend-edit-grid" action="php/manage-orders.php" method="POST">
                <input type="hidden" name="id" value="<?php echo h($row['order_id']); ?>">
                <section class="admin-panel backend-edit-main" style="grid-column: 1 / -1;">
                    <div class="form-row">
                        <div class="field">
                            <label>User ID</label>
                            <input type="text" name="user_id" value="<?php echo h($row['user_id']); ?>" required>
                        </div>
                        <div class="field">
                            <label>Controller ID</label>
                            <input type="text" name="controller_id" value="<?php echo h($row['controller_id']); ?>" required>
                        </div>
                    </div>
                    <div class="form-row" style="margin-top: 14px;">
                        <div class="field">
                            <label>Quantity</label>
                            <input type="number" name="quantity" value="<?php echo h($row['quantity']); ?>" required>
                        </div>
                        <div class="field">
                            <label>Status</label>
                            <select name="status">
                                <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
                                <option value="processing" <?php if($row['status']=='processing') echo 'selected'; ?>>Processing</option>
                                <option value="shipped" <?php if($row['status']=='shipped') echo 'selected'; ?>>Shipped</option>
                                <option value="cancelled" <?php if($row['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </section>
                <div class="backend-actions" style="grid-column: 1 / -1;">
                    <button type="submit" name="update_full_order" class="btn">Save All Changes</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>