<?php
session_start();
include "../includes/db_connect.php";

// Pastikan admin sahaja yang boleh memadam
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Menggunakan Prepared Statement untuk keselamatan
    $stmt = mysqli_stmt_init($conn);
    $sql = "DELETE FROM controllers WHERE controller_id = ?";
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        
        // Redirect balik ke dashboard inventori
        header("Location: ../frontend/dashboard_admin.php?status=deleted");
    } else {
        echo "Ralat: " . mysqli_error($conn);
    }
}
exit();
?>
