<?php
session_start();
include "../includes/db_connect.php";

// Pastikan hanya admin boleh memadam
if (isset($_GET['id']) && $_SESSION['role'] === 'admin') {
    $id = $_GET['id'];
    $stmt = mysqli_stmt_init($conn);
    // Menggunakan user_id seperti dalam database
    $sql = "DELETE FROM users WHERE user_id = ?";
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        header("Location: ../home.php?page=users&status=deleted");
    }
}
exit();
?>