<?php
session_start();
include_once __DIR__ . "/../includes/db_connect.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    die("Access Denied.");
}

if(isset($_POST['order_id']) && isset($_POST['status'])){
    $id = $_POST['order_id'];
    $status = $_POST['status'];
    
    // Gunakan prepared statement untuk keselamatan
    $stmt = mysqli_prepare($conn, "UPDATE preorders SET status=? WHERE order_id=?");
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    
    if(mysqli_stmt_execute($stmt)){
        // Redirect kembali ke home.php dengan page staff_dashboard
        header("Location: ../home.php?page=staff_dashboard");
        exit();
    } else {
        echo "Error updating record.";
    }
}
?>