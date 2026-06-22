<?php
// Sambungan ke database 
include "../includes/db_connect.php";
session_start();

// Validasi keselamatan: Pastikan hanya admin boleh mengakses [cite: 314, 368]
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// 1. LOGIK UPDATE (Untuk edit semua variable)
if (isset($_POST['update_full_order'])) {
    $id = $_POST['id'];
    $u_id = $_POST['user_id'];
    $c_id = $_POST['controller_id'];
    $qty = $_POST['quantity'];
    $status = $_POST['status'];

    // Menggunakan Prepared Statement untuk keselamatan [cite: 331]
    $stmt = mysqli_stmt_init($conn);
    $sql = "UPDATE preorders SET user_id=?, controller_id=?, quantity=?, status=? WHERE order_id=?";
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiisi", $u_id, $c_id, $qty, $status, $id);
        mysqli_stmt_execute($stmt);
        // Kembali ke dashboard dengan status berjaya [cite: 351]
        header("Location: ../home.php?status=updated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit();
}

// 2. LOGIK DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, "DELETE FROM preorders WHERE order_id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    header("Location: ../home.php?status=deleted");
    exit();
}
?>