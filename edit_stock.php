<?php
// 1. Setup ralat dan sesi (Hanya sekali sahaja)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 2. Sambungan Database
include "includes/db_connect.php"; 

// 3. Pastikan ID wujud
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID tidak diterima. URL anda: edit_stock.php?id=" . ($_GET['id'] ?? 'kosong'));
}

$id = $_GET['id'];

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// 4. Gunakan prepared statement untuk ambil data
$stmt = mysqli_stmt_init($conn);
$sql = "SELECT * FROM controllers WHERE controller_id = ?";
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("Error: Rekod tidak dijumpai dalam database.");
    }
} else {
    die("Error: Gagal menyediakan query database.");
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
<body>
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
