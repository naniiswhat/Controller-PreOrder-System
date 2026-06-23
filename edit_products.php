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

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Ambil data sedia ada
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, "SELECT * FROM controllers WHERE controller_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    die("Controller not found.");
}

$images = [];
$image_stmt = mysqli_prepare($conn, "SELECT image_path FROM controller_images WHERE controller_id=? ORDER BY sort_order, image_id");

if ($image_stmt) {
    mysqli_stmt_bind_param($image_stmt, "i", $id);
    mysqli_stmt_execute($image_stmt);
    $image_result = mysqli_stmt_get_result($image_stmt);

    while ($image = mysqli_fetch_assoc($image_result)) {
        $images[] = $image['image_path'];
    }

    mysqli_stmt_close($image_stmt);
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
    <h2>Edit Inventory: <?php echo h($row['model_name']); ?></h2>
    <?php if ($images): ?>
        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
            <?php foreach ($images as $image): ?>
                <img src="frontend/<?php echo h($image); ?>" alt="<?php echo h($row['model_name']); ?>" style="width:120px; height:90px; object-fit:cover; border:1px solid #ddd; border-radius:8px;">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="php/update_stock.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo h($row['controller_id']); ?>">
        
        <label>Model Name:</label>
        <input type="text" name="model_name" value="<?php echo h($row['model_name']); ?>" required>

        <label>Price (RM):</label>
        <input type="number" step="0.01" name="price" value="<?php echo h($row['price']); ?>" required>

        <label>Stock Quantity:</label>
        <input type="number" name="stock" value="<?php echo h($row['stock_quantity']); ?>" required>

        <label>Add Product Images:</label>
        <input type="file" name="product_images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
        <small>Images are appended to this controller's folder.</small>
        
        <button type="submit" name="update_product">Update Stock/Product</button>
    </form>
    <br>
    <a href="home.php?page=inventory">Back to Inventory</a>
</div>

</body>
</html>
