<?php
session_start();
include "includes/db_connect.php";

// making sure only Admin or Staff can access
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: index.php");
    exit();
}

// get ID from URL
if (!isset($_GET['id'])) {
    die("Error: No Controller ID provided.");
}

$id = $_GET['id'];

// Fetch existing data
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, "SELECT * FROM controllers WHERE controller_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    die("Controller not found.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Controller Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5 d-flex justify-content-center">
        <div class="card shadow p-4 w-100" style="max-width: 500px;">
            <h3 class="mb-4 text-center">Edit Inventory: <br><span class="text-primary"><?php echo $row['model_name']; ?></span></h3>
            
            <form action="php/update_stock.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['controller_id']; ?>">
                
                <div class="mb-3">
                    <label class="form-label">Model Name</label>
                    <input type="text" class="form-control" name="model_name" value="<?php echo $row['model_name']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (RM)</label>
                    <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $row['price']; ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control" name="stock" value="<?php echo $row['stock_quantity']; ?>" required>
                </div>
                
                <button type="submit" name="update_product" class="btn btn-primary w-100 mb-2">Update Product Data</button>
                <a href="home.php" class="btn btn-outline-secondary w-100">Cancel & Return</a>
            </form>
        </div>
    </div>
</body>
</html>