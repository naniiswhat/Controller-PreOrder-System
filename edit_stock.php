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
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Edit Inventory: <?php echo htmlspecialchars($row['model_name']); ?></h2>
    <form action="php/update_stock.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['controller_id']; ?>">
        <label>Model Name:</label><br>
        <input type="text" name="model_name" value="<?php echo htmlspecialchars($row['model_name']); ?>" required><br>
        
        <label>Price (RM):</label><br>
        <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required><br>
        
        <label>Stock Quantity:</label><br>
        <input type="number" name="stock" value="<?php echo $row['stock_quantity']; ?>" required><br>
        
        <button type="submit" name="update_product">Save Changes</button>
    </form>
</body>
</html>