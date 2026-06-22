<?php
session_start();
include "../includes/db_connect.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Pastikan hanya admin boleh memadam
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = mysqli_stmt_init($conn);
    // Menggunakan user_id seperti dalam database
    $sql = "DELETE FROM users WHERE user_id = ?";
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        header("Location: ../frontend/dashboard_admin.php?status=deleted");
    }
}
exit();
?>
