<?php
// error and sesh setup
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "includes/db_connect.php"; 

// make sure id exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID not accepted. Your URL: edit_stock.php?id=" . ($_GET['id'] ?? 'empty'));
}

$id = $_GET['id'];

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// prepared statement to retrieve data
$stmt = mysqli_stmt_init($conn);
$sql = "SELECT * FROM controllers WHERE controller_id = ?";
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("Error: Record not found in database.");
    }
} else {
    die("Error: Failed to prepare database query.");
}

$images = [];
$image_stmt = mysqli_prepare($conn, "SELECT image_id, image_path FROM controller_images WHERE controller_id=? ORDER BY sort_order, image_id");

if ($image_stmt) {
    mysqli_stmt_bind_param($image_stmt, "i", $id);
    mysqli_stmt_execute($image_stmt);
    $image_result = mysqli_stmt_get_result($image_stmt);

    while ($image = mysqli_fetch_assoc($image_result)) {
        $images[] = $image; 
    }
    mysqli_stmt_close($image_stmt);
}
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Edit Inventory: <?php echo h($row['model_name']); ?></h2>
    
    <form action="php/update_stock.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo h($row['controller_id']); ?>">
        
        <?php if ($images): ?>
            <label>Current Images (Check to Delete):</label><br>
            <div style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom:15px; margin-top:5px;">
                <?php foreach ($images as $img): ?>
                    <div style="text-align: center; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                        <img src="frontend/<?php echo h($img['image_path']); ?>" alt="Controller Image" style="width:120px; height:90px; object-fit:cover;"><br>
                        <input type="checkbox" name="delete_images[]" value="<?php echo h($img['image_id']); ?>"> Delete
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label>Model Name:</label><br>
        <input type="text" name="model_name" value="<?php echo h($row['model_name']); ?>" required><br>
        
        <label>Price (RM):</label><br>
        <input type="number" step="0.01" name="price" value="<?php echo h($row['price']); ?>" required><br>
        
        <label>Stock Quantity:</label><br>
        <input type="number" name="stock" value="<?php echo h($row['stock_quantity']); ?>" required><br>

        <label>Add Product Images:</label><br>
        <input type="file" name="product_images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple><br>
        <small>Images are appended to this controller's folder.</small><br><br>
        
        <button type="submit" name="update_product">Save Changes</button>
    </form>
</body>
</html>