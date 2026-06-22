<?php
session_start();
// Pastikan laluan ini betul (keluar dari folder php/ baru masuk ke includes/)
include "../includes/db_connect.php"; 

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../frontend/login.php");
    exit();
}

function inventory_redirect() {
    if ($_SESSION['role'] === 'admin') {
        return "../frontend/dashboard_admin.php";
    }

    return "../frontend/dashboard_staff.php";
}

if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['model_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Prepared Statement untuk keselamatan
    $stmt = mysqli_stmt_init($conn);
    $sql = "UPDATE controllers SET model_name=?, price=?, stock_quantity=? WHERE controller_id=?";
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sdii", $name, $price, $stock, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Berjaya, kembali ke halaman inventori
            header("Location: " . inventory_redirect() . "?status=updated");
            exit();
        } else {
            echo "Error executing query: " . mysqli_stmt_error($stmt);
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    echo "Borang tidak dihantar dengan betul.";
}
?>
