<?php
session_start();
// Pastikan laluan ke db_connect.php betul (keluar dari folder php/ dan masuk ke includes/)
include "../includes/db_connect.php";

// 1. Keselamatan: Hanya Admin atau Staff boleh tambah variant
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    die("Access Denied: Anda tidak mempunyai kebenaran.");
}

// 2. Semak sama ada borang dihantar melalui POST
if (isset($_POST['add_variant'])) {
    
    // Ambil data dari form
    $model_name = $_POST['model_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // 3. Masukkan data menggunakan Prepared Statement (Mencegah SQL Injection)
    $stmt = mysqli_stmt_init($conn);
    $sql = "INSERT INTO controllers (model_name, price, stock_quantity) VALUES (?, ?, ?)";

    if (mysqli_stmt_prepare($stmt, $sql)) {
        // "sdi" bermaksud: string, double, integer
        mysqli_stmt_bind_param($stmt, "sdi", $model_name, $price, $stock);
        
        if (mysqli_stmt_execute($stmt)) {
            // Berjaya: Redirect balik ke dashboard dengan status sukses
            header("Location: ../home.php?page=inventory&status=success");
        } else {
            echo "Gagal menyimpan data: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Ralat pada query SQL: " . mysqli_error($conn);
    }
} else {
    // Jika user cuba akses fail ini secara terus melalui URL
    header("Location: ../home.php?page=inventory");
}
exit();
?>