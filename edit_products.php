<?php
session_start();
include "includes/db_connect.php";

// Pastikan hanya Admin atau Staff yang boleh akses
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    die("Access Denied");
}

// Dapatkan ID dari URL
if (!isset($_GET['id'])) {
    die("Error: No Controller ID provided.");
}

$id = $_GET['id'];

// Ambil data sedia ada
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
<html>
<head>
    <title>Edit Controller</title>
    <style>
        .container { max-width: 500px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; font-family: sans-serif; }
        input, select { width: 100%; padding: 8px; margin: 10px 0; }
        button { background: #007bff; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Inventory: <?php echo $row['model_name']; ?></h2>
    <form action="php/update_stock.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['controller_id']; ?>">
        
        <label>Model Name:</label>
        <input type="text" name="model_name" value="<?php echo $row['model_name']; ?>" required>

        <label>Price (RM):</label>
        <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required>

        <label>Stock Quantity:</label>
        <input type="number" name="stock" value="<?php echo $row['stock_quantity']; ?>" required>
        
        <button type="submit" name="update_product">Update Stock/Product</button>
    </form>
    <br>
    <a href="home.php?page=inventory">Back to Inventory</a>
</div>

</body>
</html>